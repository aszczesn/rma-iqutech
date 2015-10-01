ALTER TABLE `iqtrack`.`rma` 
ADD COLUMN `status` VARCHAR(45) NOT NULL DEFAULT 'Pre-Alert' AFTER `supplier_ship_to_address_id`;
