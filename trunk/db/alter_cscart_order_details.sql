ALTER TABLE cscart_order_details ADD product_name VARCHAR(255) AFTER product_id;

ALTER TABLE cscart_order_details ADD product_options VARCHAR(255) AFTER product_name;