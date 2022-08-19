---------------------------------------------------EDIT GALERY
--CAMBIAR FOTOS DE UN PRODUCTO
update galery
set photo_galery = 'new photo'
where product_galery = '12312300'; 
--FIN

---------------------------------------------------DELETE GALERY
--QUITAR RELACION DE PRODUCTO CON FOTOS (NO BORRA LA FOTO DE LA BD)
delete galery
where product_galery = '12312300'; 
--FIN

--QUITAR UNA FOTO DEL PRODUCTO (NO BORRA LA FOTO DE LA BD)
delete galery
where product_galery = '12312300' and photo_galery = '911'; 
--FIN