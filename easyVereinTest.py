{
    "user": {
        "id": 123,
        "name": "John Doe",
        "email": "johndoe@example.com",
        "address": {
            "street": "123 Main St",
            "city": "Exampleville",
            "zipcode": "12345"
        }
    }
}



import json
import requests

url = "https://example.com/api/data"
response = requests.get(url)

if response.status_code == 200:
    data = json.loads(response.text)
    
    # Accessing nested elements
    user_id = data['user']['id']
    user_name = data['user']['name']
    user_email = data['user']['email']
    
    address = data['user']['address']
    street = address['street']
    city = address['city']
    zipcode = address['zipcode']
    
    print(f"User ID: {user_id}")
    print(f"User Name: {user_name}")
    print(f"User Email: {user_email}")
    print(f"Street: {street}")
    print(f"City: {city}")
    print(f"Zipcode: {zipcode}")
else:
    print(f"Failed to retrieve content. Status code: {response.status_code}"
