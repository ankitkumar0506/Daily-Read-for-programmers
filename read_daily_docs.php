<?php
// 2026-08-23 02:44:55

/* PHP
Topic: PHP PDO Prepared Statements for Secure Database Access  

Explanation:  
Prepared statements separate SQL code from data, preventing SQL injection attacks by sending the query structure to the server first and then binding user‑supplied values. PDO (PHP Data Objects) provides a consistent interface for many database systems, making it easier to write portable code. Using placeholders (named or positional) allows the same statement to be executed multiple times with different data efficiently. Errors can be handled with exceptions, giving clear debugging information. The approach also improves performance for repeated queries because the database can reuse the compiled statement.  

Code example with comments:  

<?php
// Create a new PDO instance with error mode set to exceptions
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'secret';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define a SQL query with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data that might come from a form
$userData = [
    'username' => 'johndoe',
    'email'    => 'john@example.com',
];

// Bind values and execute the statement
$stmt->execute($userData);

// Check if the insertion succeeded
if ($stmt->rowCount() === 1) {
    echo "User inserted successfully.";
} else {
    echo "Insert failed.";
}
?>
*/

/* Laravel
Topic: Laravel Queues and Jobs

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or calling external APIs to a background process. By pushing a job onto a queue, the main request can respond quickly while the work is handled asynchronously. Laravel provides a unified API for different queue drivers (database, Redis, SQS, etc.) and includes built‑in support for retries and failed job handling. Jobs are simple PHP classes that implement the ShouldQueue contract and contain a handle method where the task logic lives. Workers run continuously, pulling jobs from the queue and executing them in isolation from the HTTP request lifecycle.

Code example (Job class and dispatching it):

<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\WelcomeMail;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userEmail;

    // Constructor receives data needed for the job
    public function __construct($email)
    {
        $this->userEmail = $email;
    }

    // This method is called by the queue worker
    public function handle()
    {
        // Build and send the email – this runs in the background
        Mail::to($this->userEmail)->send(new WelcomeMail());
    }
}

// Dispatching the job from a controller or service
// The job will be placed onto the default queue connection
$userEmail = 'newuser@example.com';
SendWelcomeEmail::dispatch($userEmail);
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary named result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs make complex queries easier to read by breaking them into logical parts. They are defined using the WITH clause and exist only for the duration of the statement. When a CTE references itself, it becomes a recursive CTE, useful for traversing hierarchical data such as organizational charts or tree structures. Recursive CTEs consist of an anchor member (the base case) and a recursive member that repeatedly references the CTE until a termination condition is met.

Code example (recursive CTE to list an employee hierarchy):

-- Employees table: id, name, manager_id (null if top‑level manager)
CREATE TABLE employees (
    id INT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    manager_id INT NULL,
    FOREIGN KEY (manager_id) REFERENCES employees(id)
);

-- Insert sample data
INSERT INTO employees (id, name, manager_id) VALUES
(1, 'Alice', NULL),   -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'Dave', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to retrieve all subordinates of a given manager (e.g., Alice, id=1)
WITH RECURSIVE hierarchy AS (
    -- Anchor member: start with the selected manager
    SELECT id, name, manager_id, 0 AS level
    FROM employees
    WHERE id = 1

    UNION ALL

    -- Recursive member: find employees whose manager_id matches any id already in the hierarchy
    SELECT e.id, e.name, e.manager_id, h.level + 1
    FROM employees e
    JOIN hierarchy h ON e.manager_id = h.id
)
SELECT id, name, manager_id, level
FROM hierarchy
ORDER BY level, id;   -- result shows Alice at level 0, her direct reports at level 1, etc.
*/

/* JavaScript
Topic: Event Delegation in JavaScript  

Explanation:  
Event delegation is a technique where a single event listener is attached to a parent element instead of many individual child elements. The listener takes advantage of event bubbling to capture events that originate from its descendants. This reduces memory usage and improves performance, especially when dealing with large or dynamically generated lists. It also simplifies code maintenance because you don’t need to add or remove listeners as children are added or removed. Use event.target inside the handler to determine which child triggered the event.

Code example (with comments):

// Parent container that holds many list items
var listContainer = document.getElementById('menu');

// Attach one click listener to the container
listContainer.addEventListener('click', function(event) {
    // Check if the clicked element is a list item (or has a specific class)
    if (event.target && event.target.matches('li.menu-item')) {
        // Perform the desired action for the clicked item
        console.log('You clicked on menu item:', event.target.textContent);
        // Example: toggle a selected class
        event.target.classList.toggle('selected');
    }
});

// HTML structure (for reference):
// <ul id="menu">
//     <li class="menu-item">Home</li>
//     <li class="menu-item">About</li>
//     <li class="menu-item">Contact</li>
// </ul>
*/

/* AI
Topic: OpenAI Function Calling API in Python

Explanation:  
OpenAI’s function calling lets a language model suggest structured data that your code can act upon, bridging natural language and programmatic actions. You define a JSON schema for the function, and the model returns arguments matching that schema when appropriate. This reduces parsing errors compared to free‑form text extraction. The workflow involves sending a user prompt, receiving a response with a "function_call" field, and then invoking the real function with the parsed arguments. It is especially useful for building assistants that can query databases, schedule events, or control applications directly from chat. Proper error handling around the function call ensures resilience when the model returns incomplete or unexpected arguments.

Code example (Python, using the openai library):

import openai

# Set your API key (replace with your actual key or use environment variable)
openai.api_key = "sk-YOUR_API_KEY"

# Define the function schema the model can call
def get_weather(city: str, date: str) -> str:
    """Placeholder that pretends to fetch weather data."""
    # In a real implementation, query a weather service here
    return f"The weather in {city} on {date} will be sunny with a high of 25°C."

# Create the JSON description of the function for the model
weather_function = {
    "name": "get_weather",
    "description": "Retrieve the weather forecast for a given city and date.",
    "parameters": {
        "type": "object",
        "properties": {
            "city": {"type": "string", "description": "Name of the city"},
            "date": {"type": "string", "description": "Date in YYYY-MM-DD format"}
        },
        "required": ["city", "date"]
    }
}

# User query that may require a function call
user_message = {"role": "user", "content": "Will it rain in Paris tomorrow?"}

# Send the request with the function definition
response = openai.ChatCompletion.create(
    model="gpt-4-0613",
    messages=[user_message],
    functions=[weather_function],
    function_call="auto"  # Let the model decide when to call
)

# Extract the model's decision
message = response["choices"][0]["message"]

if message.get("function_call"):
    # Parse the arguments supplied by the model
    import json
    function_name = message["function_call"]["name"]
    arguments = json.loads(message["function_call"]["arguments"])

    # Call the actual Python function
    if function_name == "get_weather":
        result = get_weather(arguments["city"], arguments["date"])
        # Send the function result back to the model (optional, for follow‑up)
        follow_up = openai.ChatCompletion.create(
            model="gpt-4-0613",
            messages=[
                user_message,
                message,
                {"role": "function", "name": function_name, "content": result}
            ]
        )
        print(follow_up["choices"][0]["message"]["content"])
else:
    # Model responded without needing a function call
    print(message["content"])
*/

