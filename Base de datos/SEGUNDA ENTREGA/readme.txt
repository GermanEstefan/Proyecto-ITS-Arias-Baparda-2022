STG - Ambiente de Staging - pruebas sobre la base de datos

Referencias:

LLAVES PRIMARIAS
TABLA                   PK
CATEGORY            id_category
DESING              id_desing
PAYMENT METHOD      id_pay_meth
DELIVERY TIME       id_delivery
DISBURSE            id_disburse
SIZE                id_size
DISCOUNT            id_discount
SUPPLIER            id_supplier
USER                id_users
EMPLOYEE            ci
ROLE                name_role
CLIENT              user_represent
PHOTO               id_photo
GALERY              id_photo + barcode
PRODUCT             barcode
PROMO               PROMO ES UN PRODUCTO(barcode) + QUE CONTIENE UNA LISTA DE PRODUCTOS (barcode)
SALE                id_sale
STATUS              id_status
REPORT              id_sale + id_status
SALE DETAIL         id_sale + barcode
SUPPLY              id_supply
SUPPLY DETAIL       id_supply + barcode

