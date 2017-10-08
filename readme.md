# Elasticsearch

````
PUT app_index
PUT app_index/_mapping/tags
{
  "properties": {
    "id": {
      "type": "integer"
  	},
    "name": {
  	  "type": "string"
    },
    "title": {
  	  "type": "string"
    },    
	  "content": {
  	  "type": "text"
  	}
  }
}
````
