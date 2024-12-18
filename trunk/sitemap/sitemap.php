<?php

/*
 * To get sitemap XML
 */

define('AREA', 'C');
define('AREA_NAME', 'customer');
require  dirname(__FILE__) . '/../prepare.php';
require  dirname(__FILE__) . '/../init.php';

$func = $_REQUEST['xml'];



if($func == 'category_sitemap')
{
        $query = "SELECT CONCAT(seo_path,'.html') as seo_name FROM cscart_categories WHERE status='A'";
        $result = db_get_array($query);

        $file = "category_sitemap.xml";
        $url = "http://www.shopclues.com/";	
        $changefreq = Registry::get('config.sitemap_changefreq');
        $priority = Registry::get('config.sitemap_priority');
          $pf = fopen ($file, "w+");
          if (!$pf)
          {
              echo "cannot create $file\n";
              exit;
          }

          $xml_data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <urlset
            xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
            xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
            xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
                  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">
        <url>
        <loc>".$url."</loc>
        </url>";

          foreach ($result as $value) {
            $xml_data .= "
        <url>
        <loc>".$url.$value['seo_name']."</loc>
        </url>";
        }

        $xml_data .= "</urlset>";
        fwrite($pf, $xml_data);
}
elseif($func == 'product_sitemap')
{
        $count = db_get_field("select count(*) from cscart_products where status='A'");
        $row = Registry::get('config.sitemap_product_limit');
        $limit = 0;
        $filenamecounter =1;
        for($i =0;$i<$count;$i = $i+$row)
        {
            
        $query = "SELECT csn.name as seo_name FROM cscart_seo_names csn INNER JOIN cscart_products cp ON cp.product_id = csn.object_id and cp.status='A' WHERE csn.type='p' limit ".$limit." , ".$row;
        $result = db_get_array($query);

        $file = "product_sitemap".$filenamecounter.".xml";
        $url = "http://www.shopclues.com/";	
        $changefreq = Registry::get('config.sitemap_changefreq');
        $priority = Registry::get('config.sitemap_priority');
          $pf = fopen ($file, "w+");
          if (!$pf)
          {
              echo "cannot create $file\n";
              exit;
          }

          $xml_data = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <urlset
            xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
            xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
            xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
                  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">
        <url>
        <loc>".$url."</loc>
        </url>";

          foreach ($result as $value) {
            $xml_data .= "
        <url>
        <loc>".$url.$value['seo_name']."</loc>
        </url>";
        }

        $xml_data .= "</urlset>";
        fwrite($pf, $xml_data);
            
        $limit = $limit + $row;
        $filenamecounter++;
            
        }
        
}

?>