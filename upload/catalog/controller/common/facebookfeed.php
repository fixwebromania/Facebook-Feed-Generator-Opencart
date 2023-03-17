<?php
//Opencart Product feed for Facebook product catalog
//Author: Toader Bogdan
//Version: 2.0
//Email: bogdantoa@yahoo.com
class ControllerCommonFacebookFeed extends Controller
{
	private function clean($string)
	{
		$string=str_ireplace(PHP_EOL,"",$string);
		$string=strip_tags(html_entity_decode($string));
		$string=str_ireplace('"',"",$string);
		return preg_replace("/\s+/"," ",$string);
	}
	public function index()
	{
		header("Content-Type: text/csv"); 
		header("Content-Disposition: attachment; filename=faceboook-feed.csv");
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");	
		print "id,title,description,google_product_category,link,image_link,availability,price,brand,condition,google_product_category".PHP_EOL;
		$this->load->model('catalog/product');
		$products = $this->model_catalog_product->getProducts();
		foreach($products as $product)
		{
			$price=($product['special'] ? $product['special'] :$product['price']);
			
			$csv_line ='"'.$product['product_id'].'",';
			$csv_line.='"'.$this->clean($product['name']).'",';
			$csv_line.='"'.$this->clean($product['description']).'",';
			$csv_line.='"",';
			$csv_line.='"'.str_ireplace("&amp;","&",$this->url->link('product/product', 'product_id='.$product['product_id'])).'",';
			$csv_line.='"'.trim(HTTP_SERVER,"/")."/image/".$product['image'].'",';
			$csv_line.='"'.($product['status']==1 ? 'in stock':'out of stock').'",';
			$csv_line.='"'.round($this->tax->calculate($price,$product['tax_class_id'], $this->config->get('config_tax')),2).' '.$this->session->data['currency'].'",';
			$csv_line.='"'.(empty($product['manufacturer']) ? $product['name'] : $product['manufacturer']).'",';
			$csv_line.='"new",""';
			print $csv_line.PHP_EOL;
		}
	}
}
?>