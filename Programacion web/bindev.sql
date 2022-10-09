select*, d.name ,s.name, c.name from product pd
inner join cateogory c
inner join design d
inner join size s
on p.product_category = c.category,p.product_design = .id_design