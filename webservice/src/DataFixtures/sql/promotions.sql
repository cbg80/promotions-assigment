USE `promotions-assigment`;
insert into `promotion` (`id`, `discount_percentage`, `description`) values 
(uuid(), '30.00', "Apply to products in the 'boots' category"),
(uuid(), '15.00', "Apply to product whose 'sku' is 000003")
;