# Elasticsearch

````
PUT app_index
PUT app_index/_mapping/products
{
  "properties": {
    "id": {
      "type": "integer"
  	},
    "title": {
      "type": "string"
    },
	"description": {
  	  "type": "text"
  	},  
	 "price": {
  	  "type": "string"
  	 },
	 "condition": {
  	  "type": "string"
  	 },
	 "availability": {
  	  "type": "string"
  	 },
	 "brand": {
  	  "type": "string"
  	 },  	
	 "product_type": {
  	  "type": "string"
  	 }    	   	 
  }
}
````
