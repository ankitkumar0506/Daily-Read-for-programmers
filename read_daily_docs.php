<?php
// 2026-10-14 06:29:49

/* PHP
Topic: PHP Anonymous Functions (Closures)

Explanation:
Anonymous functions, also called closures, allow you to create functions without giving them a name and assign them to variables. They can capture variables from the surrounding scope, enabling powerful patterns like callbacks and functional pipelines. Closures are especially useful when you need a short piece of logic that will be passed to array functions such as array_map or usort. PHP supports the use keyword to explicitly import variables by value or by reference into the closure's scope. Because they are objects, you can store them, return them from other functions, or even bind them to different objects using the bindTo method.

Code Example:
// Define an array of numbers
$numbers = [1, 2, 3, 4, 5];

// Create an anonymous function that multiplies each element by a factor
$factor = 3;
$multiply = function($value) use ($factor) {
    // $factor is captured from the parent scope
    return $value * $factor;
};

// Apply the closure to each element using array_map
$doubled = array_map($multiply, $numbers);

// Output the result
print_r($doubled); // Expected output: Array ( [0] => 3 [1] => 6 [2] => 9 [3] => 12 [4] => 15 )
*/

/* Laravel
Topic: Eloquent Mutators & Accessors  

Explanation:  
- Mutators and accessors allow you to transform attribute values when they are set or retrieved on an Eloquent model.  
- A mutator is defined by a set{Attribute}Attribute method and runs automatically when you assign a value to that attribute.  
- An accessor is defined by a get{Attribute}Attribute method and runs automatically when you read the attribute’s value.  
- They are useful for formatting data, encrypting fields, or converting data types without cluttering controllers.  
- Mutators and accessors work with both standard attributes and JSON columns stored as arrays.  

Code example (User model with a password mutator and a formatted created_at accessor):

<?php  
namespace App\Models;  

use Illuminate\Foundation\Auth\User as Authenticatable;  
use Illuminate\Notifications\Notifiable;  

class User extends Authenticatable  
{  
    use Notifiable;  

    // Define which attributes are mass assignable  
    protected $fillable = ['name', 'email', 'password'];  

    // Mutator: automatically hash the password when it is set  
    public function setPasswordAttribute($value)  
    {  
        // If the password is already hashed, leave it unchanged  
        $this->attributes['password'] = \Hash::needsRehash($value) ? \Hash::make($value) : $value;  
    }  

    // Accessor: format the created_at timestamp when accessed  
    public function getCreatedAtAttribute($value)  
    {  
        // Return a human‑readable date string, e.g., “Mar 10, 2024 14:35”  
        return \Carbon\Carbon::parse($value)->format('M d, Y H:i');  
    }  
}  

// Usage in a controller or tinker  
$user = new \App\Models\User();  
$user->name = 'Alice';  
$user->email = 'alice@example.com';  
$user->password = 'secret123';  // The mutator hashes this value automatically  
$user->save();  

echo $user->created_at;  // The accessor outputs the formatted date string.  
*/

/* MySQL
Topic: Stored Procedures in MySQL

Explanation:  
Stored procedures are named sets of SQL statements that are stored on the server and can be invoked repeatedly. They allow you to encapsulate business logic, reduce client‑server round trips, and improve security by limiting direct table access. Parameters can be passed in, out, or both, enabling flexible input and output handling. Using stored procedures can also help with code reuse and simplify complex transaction management. They are compiled once and then executed many times, offering performance benefits over ad‑hoc queries.

Code example with comments:

-- Switch to a custom delimiter so the procedure body can contain semicolons
DELIMITER $$

-- Create a stored procedure that retrieves all orders for a given customer
CREATE PROCEDURE GetCustomerOrders (IN cust_id INT)
BEGIN
    -- Select the order details for the supplied customer ID
    SELECT order_id,
           order_date,
           total_amount
    FROM orders
    WHERE customer_id = cust_id;
END$$

-- Restore the default delimiter
DELIMITER ;

-- Call the procedure for a specific customer (e.g., customer with ID 42)
CALL GetCustomerOrders(42);
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to its lexical environment even after the outer function has finished executing.  
It allows inner functions to read variables from the outer scope, preserving state across multiple calls.  
Closures are created every time a function is defined, enabling data encapsulation and private variables.  
They are heavily used in callbacks, event handlers, and module patterns.  
Understanding closures helps avoid common pitfalls like unintended variable sharing in loops.

Code Example (with comments):
function makeCounter() {                 // outer function creates a private variable
    let count = 0;                       // this variable is not directly accessible from outside

    return function() {                  // inner function forms a closure over count
        count++;                         // modifies the private variable
        console.log('Current count:', count);
    };
}

const counterA = makeCounter();          // each call creates a new independent closure
const counterB = makeCounter();

counterA(); // Current count: 1
counterA(); // Current count: 2
counterB(); // Current count: 1   (separate closure, its own count)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs before the actual user query, guiding it toward the desired behavior without fine‑tuning. By framing the prompt as a dialogue (system, user, assistant messages) you can control tone, format, and domain knowledge. This technique works well for tasks like data extraction, code generation, or style‑consistent writing. The examples act as in‑context “training”, allowing the model to infer the pattern you expect. Adjusting the number and clarity of examples lets you trade off between prompt length and output quality.

Code example (Python, using the official openai library):  

import os  
import openai  

# Set your API key – replace with a secure method in production  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def extract_entities(text: str) -> str:  
    # Define a few in‑context examples that show the desired JSON output format  
    messages = [  
        {  
            "role": "system",  
            "content": "You are an assistant that extracts named entities (people, locations, organizations) from a sentence and returns a JSON object with keys 'people', 'locations', 'organizations'."  
        },  
        {  
            "role": "user",  
            "content": "Barack Obama visited Berlin in 2016."  
        },  
        {  
            "role": "assistant",  
            "content": '{"people": ["Barack Obama"], "locations": ["Berlin"], "organizations": []}'  
        },  
        {  
            "role": "user",  
            "content": "Google opened a new office in Toronto."  
        },  
        {  
            "role": "assistant",  
            "content": '{"people": [], "locations": ["Toronto"], "organizations": ["Google"]}'  
        },  
        # The actual request follows the same pattern  
        {  
            "role": "user",  
            "content": text  
        }  
    ]  

    # Call the chat completion endpoint  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",          # Use a suitable model for cost‑effective inference  
        messages=messages,  
        temperature=0.0,              # Deterministic output for structured data  
        max_tokens=200               # Enough for the JSON result  
    )  

    # Extract the assistant's reply text  
    return response.choices[0].message.content.strip()  

# Example usage  
sample = "Elon Musk announced Tesla's new factory in Austin."  
print(extract_entities(sample))   # Expected JSON with person, organization, location.  
*/

