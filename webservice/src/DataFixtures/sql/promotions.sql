-- USE `promotions-assigment`;
insert into `promotion` (`id`, `discount_percentage`, `description`, `category_name`, `product_sku`) values 
(uuid(), '30.00', "Apply to products in the 'boots' category", 'boots', null),
(uuid(), '15.00', "Apply to product whose 'sku' is 000003", null, '000003')
;