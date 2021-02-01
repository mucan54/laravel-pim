<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Types\SelectOption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Scopes;
use Grayloon\Magento\Magento;
use App\Notifications\MagentoMigrationCompleted;
use Rinvex\Attributes\Models\Attribute;
use App\Models\Types\Varchar;
use App\Models\Types\MultiSelect;

class MagentoMigrate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attributes=[];

    protected $attrArray=[];

    protected $stores=[];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(\App\Models\User $user)
    {
        $magento = new Magento();
        $this->magentoWorker($magento);
        //$user->notify(new MagentoMigrationCompleted());
    }

    public function magentoWorker(\Grayloon\Magento\Magento $magento,$scopeCode=null)
    {
        $magento = new Magento();
            $i=1;
            $lastSku="";
            $currentSku="";
            $filter=['searchCriteria[filterGroups][0][filters][0][field]'=>'type_id','searchCriteria[filterGroups][0][filters][0][value]'=>'configurable'];
            while($body=$magento->api('products')->all($pageSize = 50, $currentPage = $i, $filters = $filter)->body()){
                $response = json_decode($body);
                $products = $response->items;
                $currentSku=$products[0]->sku;
                foreach($products as $item){
                    $this->productCreate($item);
                }
                if($lastSku==$currentSku)
                    break;
                else
                    $lastSku=$currentSku;
                $i++;
            }
            //$magento->api('productAttributes')->show();
    }

    public function productCreate($item,$mid=null){
        $product=new Product();
        $product->name=$item->name;
        $product->sku=$item->sku;
        $product->status=$item->status;
        $product->type_id=$item->type_id;
        $product->fill($this->attributeAttach($item->custom_attributes))->save();
        if($item->type_id=="configurable"){
            $this->createSimples($item);
        }
    }

    public function attributeAttach($attributes){
        $attributeArr=[];
        foreach ($attributes as $attribute){
            $this->mAttrCheck($attribute);
            $code = $attribute->attribute_code;
            if($this->mAttr($attribute))
                $attributeArr[$code]=$this->mAttr($attribute);
        }
        return $attributeArr;
    }

    public function getById($arr){

        $magento = new Magento();
        $filter=array('searchCriteria[filter_groups][0][filters][0][field]'=> 'entity_id',
            'searchCriteria[filter_groups][0][filters][0][value]'=> implode (", ", $arr),
            'searchCriteria[filter_groups][0][filters][0][condition_type]'=> 'in');
        $body=$magento->api('products')->all($pageSize = 50, $currentPage = 1, $filters = $filter)->body();
        $response = json_decode($body);
        $products = $response->items;
        return $products;
    }

    public function attributeCreate($attribute){

        $magento = new Magento();
        $mAttribute = json_decode($magento->api('productAttributes')->show($attribute)->body());
        $stores=$this->getStores();
        if($mAttribute->frontend_input=="text"){
            Varchar::magentoCreate($mAttribute,$stores);
            return true;
        }
        else if($mAttribute->frontend_input=="select") {
            MultiSelect::magentoCreate($mAttribute,$stores,$this->createOptionsArray($mAttribute,$attribute));
            return true;
        }else
            return false;

    }


    public function createOptionsArray($mAttribute,$attribute){
        $stores=$this->getStores();
        foreach ($stores as $store){
            $allOptions['en']=$mAttribute->options;
            $magento = new Magento();
            $magento->setStoreCode($store);
            $new_mAttribute = $magento->api('productAttributes')->show($attribute)->json();
            if(isset($new_mAttribute['options']))
                $allOptions[$store]=$new_mAttribute['options'];
        }
        return $allOptions;
    }

    public function getStores(){
        if(!$this->stores) {
            $scopes = Scopes::select('code')->where('type','magento')->get()->toArray();
            if(!$scopes)
                return "all";
            $newscope=[];
            foreach ($scopes as $a) { array_push($newscope,$a['code']); }
            $magento = new Magento();
            $stores = json_decode($magento->api('store')->get('storeViews')->body());
            $storeArr = [];
            foreach ($stores as $store) {
                if(in_array($store->code,$newscope))
                    $storeArr[$store->website_id] = $store->code;
            }
            $this->stores=$storeArr;
        }
        return $this->stores;
    }

    public function mAttr($attr){
        if(isset($this->attrArray[$attr->attribute_code])) {
            if(!$this->attrArray[$attr->attribute_code])
                return false;
            //$multi = Attribute::where('slug',$attr->attribute_code)->where('group','magento')->get();
            if(isset($this->attrArray[$attr->attribute_code][$attr->value]))
            {
                $val=SelectOption::where('content->en', $this->attrArray[$attr->attribute_code][$attr->value])->first();
                return $val->id;
            }
            else
                print($attr->attribute_code);
                echo PHP_EOL;
                print($attr->value);
                echo PHP_EOL;
                print_r($this->attrArray[$attr->attribute_code][strval($attr->value)]);

        }
        else
            return $attr->value;
    }

    public function mAttrSet($attr){
        $magento = new Magento();
        $attr = json_decode($magento->api('productAttributes')->show($attr->attribute_code)->body());
        if($attr->frontend_input=="select") {
            foreach ($attr->options as $option) {
                 $this->attrArray[$attr->attribute_code][$option->value] = $option->label;
            }
        }
    }

    public function mAttrCheck($attribute){
        if(!in_array($attribute->attribute_code,$this->attributes)){
            $elem=Attribute::where('slug',$attribute->attribute_code)->where('group', 'magento')->get();
            if(!isset($elem[0])){
                if($this->attributeCreate($attribute->attribute_code))
                    $this->mAttrSet($attribute);
                else
                    $this->attrArray[$attribute->attribute_code]=false;
                array_push($this->attributes,$attribute->attribute_code);
            }
        }
    }

    public function createSimples($item){

        $items=$this->getById($item->extension_attributes->configurable_product_links);

        foreach ($items as $item){
            $this->productCreate($item);
        }
    }
}
