<?php

/**
* Api for getting Catalog data
*/

class Catalog extends REST
{
    public function __construct()
    {
        parent::__construct();
    }

    public function cat_leaf_features()
    {
        $leaf_category_sql_result['feature_id_for_leaf'] = $this->get_leaf_categories();
        foreach($leaf_category_sql_result['feature_id_for_leaf']  as $key=>$value)
        {
            $ids = explode('/',$value['id_path']);
            foreach($ids as $key1=>$value1)
            {
                $feature_id_sql = "select cpf.feature_id,cpfd.description from
                                   cscart_product_features cpf
                                   inner join cscart_product_features_descriptions cpfd on cpf.feature_id = cpfd.feature_id and cpf.status='A'
                                  and FIND_IN_SET($value1,cpf.categories_path)";
                $feature_id_sql_result = db_get_array($feature_id_sql);
            }
            $leaf_category_sql_result['feature_id_for_leaf'][$key]['feature']=$feature_id_sql_result;
        }
        $this->response($this->json($leaf_category_sql_result), 200);
    }

    public function cat_features()
    {
        $feature_sql = "SELECT cpf.feature_id , cpfd.description ,
                        cpf.required_feature,cpfd1.description as feature_group,
                        cpf.feature_type as data_type ,cpf.is_filter_feature FROM cscart_product_features cpf
                        inner join cscart_product_features_descriptions cpfd on cpf.feature_id=cpfd.feature_id and cpf.status='A'
                        inner join cscart_product_features_descriptions cpfd1 on cpf.parent_id=cpfd1.feature_id and cpf.status='A'";
        $feature_sql_result['features'] = db_get_array($feature_sql);
        foreach($feature_sql_result['features'] as $key=>$value)
        {
            if($value['is_filter_feature']=='Y')
            {
                $feature_variant_sql = "SELECT cpfvd.variant FROM cscart_product_feature_variants cpfv
                                        inner join cscart_product_feature_variant_descriptions cpfvd on cpfv.variant_id=cpfvd.variant_id
                                        and cpfv.feature_id=$value[feature_id]";
                $feature_sql_result['features'][$key]['filter_variant'] = db_get_array($feature_variant_sql);
            }
        }
        $this->response($this->json($feature_sql_result), 200);
    }

    public function cat_category_tree()
    {
        $leaf_category_sql_result = $this->get_leaf_categories();
        $i=0;
        foreach($leaf_category_sql_result  as $key=>$value)
        {
            $id = str_replace('/',',',$value['id_path']);
            $id_name = "select category_id,category from cscart_category_descriptions where category_id in ($id) order by field(category_id,$id)";
            $id_name_result = db_get_array($id_name);
            foreach($id_name_result as $key1=>$value1)
            {
                if($key1==0)
                {
                    $category_tree['category'][$i]["metacategory"]=$value1['category_id'].','.$value1['category'];
                }
                else
                {
                    $category_tree['category'][$i]["subcategory$key1"] =$value1['category_id'].','.$value1['category'];
                }
            }
            $i++;
        }
        $this->response($this->json($category_tree), 200);
    }

    public function cat_categories()
    {
        $category_sql = "SELECT cc.category_id,ccd.category from cscart_categories cc
                         inner join cscart_category_descriptions ccd on cc.category_id=ccd.category_id
                        and cc.status='A' and cc.show_to_merchant='Y'";

        $category_sql_result['categories_descriptions'] = db_get_array($category_sql);
        $this->response($this->json($category_sql_result), 200);
    }

    public function cat_options()
    {
        $option_sql = "select cpo.option_id,CONCAT(cpod.option_name,':',cpo.option_type,
                        '[',GROUP_CONCAT(cpovd.variant_name,''),']') as option_string from cscart_product_options cpo
                        inner join cscart_product_options_descriptions cpod on cpo.option_id=cpod.option_id and cpo.status='A' and cpo.product_id=0
                        inner join cscart_product_option_variants cpov on cpov.option_id=cpo.option_id and cpov.status='A'
                        inner join cscart_product_option_variants_descriptions cpovd on cpovd.variant_id=cpov.variant_id
                        group by cpo.option_id";
        $option_sql_result['options'] = db_get_array($option_sql);
        $this->response($this->json($option_sql_result), 200);
    }

    public function cat_leaf_options()
    {
        $leaf_category_sql_result['options_for_leaf'] = $this->get_leaf_categories();
        foreach($leaf_category_sql_result['options_for_leaf']  as $key=>$value)
        {
            $ids = explode('/',$value['id_path']);
            foreach($ids as $key1=>$value1)
            {
                $global_options_sql = "select cpo.option_id,cpod.option_name from
                                        cscart_product_options cpo
                                    inner join cscart_product_options_descriptions cpod on cpo.option_id = cpod.option_id
                                    and cpo.product_id=0 and cpo.status='A' and FIND_IN_SET($value1,cpo.categories_path)";
                $global_options_sql_result = db_get_array($global_options_sql);
            }
            $leaf_category_sql_result['options_for_leaf'][$key]['option_id']=$global_options_sql_result;
        }
        $this->response($this->json($leaf_category_sql_result), 200);
    }

    public function json($data)
    {
        if(is_array($data))
        {
            return json_encode($data);
        }
    }

    private function get_leaf_categories()
    {
        $leaf_category_sql = "SELECT ccd.category,cc.id_path, cc.category_id FROM cscart_categories cc
                            inner join cscart_category_descriptions ccd using(category_id)  WHERE cc.category_id NOT IN (
                            SELECT DISTINCT parent_id FROM cscart_categories WHERE parent_id IS NOT NULL and status='A' and show_to_merchant='Y')
                            and cc.show_to_merchant='Y' and cc.status='A'";
        return db_get_array($leaf_category_sql);
    }
}