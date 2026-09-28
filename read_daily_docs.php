<?php
// 2026-09-28 07:17:47

/* PHP
Topic: Prepared Statements with PDO (PHP Data Objects)

Explanation:
Prepared statements separate the SQL query structure from its data values, allowing the database engine to compile the query once and reuse it safely with different parameters. This approach prevents SQL injection because user‑supplied values are never concatenated directly into the query string. PDO provides a uniform interface for many databases, making the code portable across MySQL, PostgreSQL, SQLite, etc. Binding parameters can be done by position or by name, and the driver handles proper quoting and escaping. Using prepared statements also improves performance when the same statement is executed repeatedly within a loop.

Code example with comments:
<?php
// Create a PDO connection (replace DSN, username, password with your own values)
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'secret';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define the SQL with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:user, :mail, :created)';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Example data that might come from a form or API
$data = [
    ['user' => 'alice',   'mail' => 'alice@example.com',   'created' => date('Y-m-d H:i:s')],
    ['user' => 'bob',     'mail' => 'bob@example.org',     'created' => date('Y-m-d H:i:s')],
    ['user' => 'charlie', 'mail' => 'charlie@sample.net', 'created' => date('Y-m-d H:i:s')],
];

// Loop over the data and execute the prepared statement with bound values
foreach ($data as $row) {
    // bindValue can be used, but passing the array to execute is shorter
    $stmt->execute([
        ':user'    => $row['user'],
        ':mail'    => $row['mail'],
        ':created' => $row['created'],
    ]);
}

// Verify insertion (optional)
echo "Inserted " . count($data) . " rows successfully.\n";
?>
*/

/* Laravel
Laravel Service Container & Dependency Injection

The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection automatically. It resolves classes, interfaces, and their dependencies at runtime, allowing you to decouple components and write testable code. By binding abstractions to concrete implementations, you can swap out services without changing the consuming code. The container also supports contextual bindings, singleton bindings, and automatic resolution of primitive dependencies. Understanding the service container is essential for building maintainable Laravel applications.

Example – binding an interface to a concrete class and injecting it into a controller:

// app/Contracts/PaymentGateway.php
<?php
namespace App\Contracts;
interface PaymentGateway {
    public function charge(float $amount);
}

// app/Services/StripeGateway.php
<?php
namespace App\Services;
use App\Contracts\PaymentGateway;
class StripeGateway implements PaymentGateway {
    public function charge(float $amount) {
        // Logic to process payment via Stripe API
        return "Charged $$amount using Stripe.";
    }
}

// app/Providers/AppServiceProvider.php
<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;
class AppServiceProvider extends ServiceProvider {
    public function register() {
        // Bind the interface to the concrete implementation
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}

// app/Http/Controllers/OrderController.php
<?php
namespace App\Http\Controllers;
use App\Contracts\PaymentGateway;
class OrderController extends Controller {
    protected $paymentGateway;
    // Laravel automatically injects the concrete class bound to PaymentGateway
    public function __construct(PaymentGateway $paymentGateway) {
        $this->paymentGateway = $paymentGateway;
    }
    public function store() {
        $amount = 99.99;
        // Use the injected service to charge the amount
        $result = $this->paymentGateway->charge($amount);
        return response()->json(['message' => $result]);
    }
}
*/

/* MySQL
Topic: MySQL Common Table Expressions (CTE) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs are defined using the WITH clause and improve query readability by allowing you to break complex logic into named subqueries. MySQL supports both non‑recursive and recursive CTEs, enabling hierarchical data processing such as organization charts or bill‑of‑materials. Recursive CTEs repeatedly execute a union of an anchor query and a recursive query until no new rows are produced. This feature eliminates the need for stored procedures or client‑side loops for many hierarchical tasks.

Code example (recursive CTE to list an employee hierarchy):

-- Define the CTE named employee_hierarchy
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with the top‑level manager (e.g., employee_id = 1)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL          -- top‑level has no manager

    UNION ALL

    -- Recursive member: join each manager to their direct reports
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        eh.level + 1 AS level
    FROM employees e
    INNER JOIN employee_hierarchy eh
        ON e.manager_id = eh.employee_id
)
-- Query the CTE to retrieve the full hierarchy ordered by level
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM employee_hierarchy
ORDER BY level, manager_id, employee_id;
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
- A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
- It allows inner functions to reference variables declared in an outer function after the outer function has finished executing.  
- Closures are created automatically every time a function is defined, and they are essential for data encapsulation and creating private state.  
- They enable patterns such as function factories, memoization, and module-like structures without using classes.  
- Understanding closures helps avoid common pitfalls like unintentionally sharing mutable variables across multiple invocations.

Code example with comments:
function makeCounter() {                 // outer function creates a private variable
    let count = 0;                       // this variable is captured by the inner function
    return function() {                 // the inner function forms a closure over count
        count++;                         // modify the private variable
        console.log('Current count:', count); // display the updated value
    };
}
const counterA = makeCounter();           // each call gets its own closure
const counterB = makeCounter();

counterA(); // Current count: 1
counterA(); // Current count: 2
counterB(); // Current count: 1   (separate private count)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI Chat Completion API  

Explanation:  
Few‑shot prompting lets a language model infer a new task from a handful of examples embedded directly in the prompt. By carefully structuring the system message and user examples, you can guide the model to produce consistent, high‑quality outputs without fine‑tuning. The technique works well for classification, transformation, or extraction tasks where labeled data is scarce. Include clear delimiters and explicit instructions so the model knows where examples end and the new query begins. Adjust temperature and max_tokens to balance creativity and determinism for reliable results.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your API key from an environment variable or configuration file  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define the system prompt that sets the role and style of the assistant  
system_prompt = """You are a helpful assistant that extracts the product name and price from a customer email.  
Return the result as a JSON object with keys "product" and "price".  
If the price is not mentioned, set its value to null."""  

# Provide two few‑shot examples that demonstrate the desired output format  
few_shot_examples = [  
    {  
        "role": "user",  
        "content": "Hi, I would like to order the UltraWidget. Please charge me $49.99."  
    },  
    {  
        "role": "assistant",  
        "content": '{ "product": "UltraWidget", "price": 49.99 }'  
    },  
    {  
        "role": "user",  
        "content": "Can you send me the MegaGadget? I need it asap."  
    },  
    {  
        "role": "assistant",  
        "content": '{ "product": "MegaGadget", "price": null }'  
    }  
]  

# New user query that the model must handle using the pattern above  
new_query = {  
    "role": "user",  
    "content": "Please add the NanoDevice to my cart. It's $12.5."  
}  

# Assemble the full message list: system prompt, few‑shot pairs, then the new query  
messages = [ {"role": "system", "content": system_prompt} ] + few_shot_examples + [new_query]  

# Call the chat completion endpoint with low temperature for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",        # choose a model that supports chat  
    messages=messages,  
    temperature=0.0,            # deterministic results  
    max_tokens=100,  
    top_p=1.0,  
    n=1                         # single best completion  
)  

# Extract and print the assistant's reply  
assistant_reply = response.choices[0].message.content  
print("Extracted JSON:", assistant_reply)  
*/

