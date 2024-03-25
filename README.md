# Symfony REST приложение для расчета цены продукта и проведения оплаты


POST: для расчёта цены
http://localhost:8080/api/v1/products/calculate-price

Пример json тела запроса:
{
"product": 1,
"taxNumber": "DE123456789",
"couponCode": "D15"
}

Пример запроса в формате curl команды:
curl --location --request POST 'http://localhost:8080/api/v1/products/calculate-price' \
--header 'Content-Type: text/plain' \
--data-raw '{
"product": 1,
"taxNumber": "DE123456789",
"couponCode": "D15"
}'

POST: для выполнения покупки
http://localhost:8080/api/v1/products/purchase

Пример json тела запроса:
{
"product": 1,
"taxNumber": "IT12345678900",
"couponCode": "D15",
"paymentProcessor": "paypal"
}

Пример запроса в формате curl команды:
curl --location --request POST 'http://localhost:8080/api/v1/products/purchase' \
--header 'Content-Type: text/plain' \
--data-raw '{
"product": 1,
"taxNumber": "IT12345678900",
"couponCode": "D15",
"paymentProcessor": "paypal"
}'