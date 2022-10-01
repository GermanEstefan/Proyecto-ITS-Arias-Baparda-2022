select p.name, pr.quantity
from product p
inner join promo pr
on pr.have_product = p.barcode

