<?php
// 2026-11-10 08:39:31

/* PHP
Topic: PHP PDO Prepared Statements  

Explanation:  
PDO (PHP Data Objects) provides a consistent interface for accessing databases in PHP.  
Prepared statements separate the SQL query from the data, which prevents SQL injection attacks.  
They are compiled once by the database server, then can be executed multiple times with different parameters, improving performance.  
Using named or positional placeholders makes the code more readable and easier to maintain.  
PDO also offers error handling through exceptions, giving developers finer control over database interactions.  

Code example (MySQL connection and a prepared SELECT query):  

<?php
// Enable exceptions for PDO errors
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

// Create a new PDO instance (replace credentials with your own)
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare a SELECT statement with a named placeholder
$sql = 'SELECT id, name, email FROM users WHERE status = :status';
$stmt = $pdo->prepare($sql);

// Bind a value to the placeholder and execute
$status = 'active';
$stmt->execute([':status' => $status]);

// Fetch all matching rows
$users = $stmt->fetchAll();

foreach ($users as $user) {
    echo "ID: {$user['id']} - Name: {$user['name']} - Email: {$user['email']}\n";
}

// Example of an INSERT using positional placeholders
$insertSql = 'INSERT INTO users (name, email, status) VALUES (?, ?, ?)';
$insertStmt = $pdo->prepare($insertSql);
$insertStmt->execute(['Jane Doe', 'jane@example.com', 'active']);

// Get the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: $newUserId\n";
?>
*/

/* Laravel
Laravel Service Container & Dependency Injection  

The service container is the core of Laravel’s inversion of control (IoC) system. It resolves class dependencies automatically, allowing you to type‑hint objects in constructors or controller methods. By binding interfaces to concrete implementations, you can swap out classes without changing the consuming code. This promotes loose coupling, easier testing, and cleaner architecture. The container can also resolve primitive values and contextual bindings for more complex scenarios.  

Example – binding an interface to an implementation and injecting it into a controller:

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripeGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the interface to the concrete class in the container
        $this->app->bind(PaymentGateway::class, StripeGateway::class);
    }
}
?>

<?php
namespace App\Contracts;

interface PaymentGateway
{
    public function charge(float $amount);
}
?>

<?php
namespace App\Services;

use App\Contracts\PaymentGateway;

class StripeGateway implements PaymentGateway
{
    public function charge(float $amount)
    {
        // Logic to charge via Stripe API
        return "Charged $$amount using Stripe.";
    }
}
?>

<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;

class CheckoutController extends Controller
{
    protected $gateway;

    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function store()
    {
        $result = $this->gateway->charge(49.99);
        return response()->json(['message' => $result]);
    }
}
?>
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTE) in MySQL  

Explanation:  
A Recursive CTE allows you to query hierarchical or tree‑structured data in a single statement.  
It consists of an anchor query that returns the first level of rows and a recursive member that references the CTE itself to produce subsequent levels.  
The recursion stops automatically when the recursive member returns no rows, preventing infinite loops.  
MySQL supports the WITH RECURSIVE clause starting from version 8.0, making it possible to walk parent‑child relationships such as organizational charts or bill‑of‑materials.  
Using a CTE keeps the query readable and avoids the need for procedural code or temporary tables.  

Code example:  
-- Sample table representing an employee hierarchy  
CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    emp_name VARCHAR(50),  
    manager_id INT NULL   -- references emp_id of the manager, NULL for top‑level boss  
);  

INSERT INTO employees (emp_id, emp_name, manager_id) VALUES  
(1, 'Alice', NULL),   -- CEO  
(2, 'Bob', 1),        -- reports to Alice  
(3, 'Carol', 1),      -- reports to Alice  
(4, 'David', 2),      -- reports to Bob  
(5, 'Eve', 2),        -- reports to Bob  
(6, 'Frank', 3);      -- reports to Carol  

-- Recursive CTE to list all subordinates of a given manager (e.g., manager_id = 1)  
WITH RECURSIVE subordinates AS (  
    -- Anchor member: start with direct reports of the chosen manager  
    SELECT emp_id, emp_name, manager_id, 1 AS level  
    FROM employees  
    WHERE manager_id = 1  
  
    UNION ALL  
  
    -- Recursive member: find employees whose manager is in the previous level  
    SELECT e.emp_id, e.emp_name, e.manager_id, s.level + 1  
    FROM employees e  
    INNER JOIN subordinates s ON e.manager_id = s.emp_id  
)  
SELECT emp_id, emp_name, manager_id, level  
FROM subordinates  
ORDER BY level, emp_id;  

-- The result shows the hierarchy under Alice with the depth (level) of each employee.  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation: A closure is a function that retains access to its lexical environment even after the outer function has finished executing. It allows the inner function to read and manipulate variables defined in the outer scope. Closures are created every time a function is defined, capturing the surrounding state at that moment. They are useful for data privacy, creating function factories, and implementing partial application. Understanding closures is essential for mastering asynchronous patterns and callback handling in JavaScript.

Code example:
// Define a function that returns another function (a closure)
function makeAdder(x) {
    // The inner function forms a closure over the variable x
    return function(y) {
        // It can use x even after makeAdder has returned
        return x + y; // returns the sum of the captured x and the argument y
    };
}

// Create a new function that adds 10 to its argument
const addTen = makeAdder(10);

// Use the closure
console.log(addTen(5));   // Output: 15
console.log(addTen(20));  // Output: 30

// Another example showing data privacy
function secretHolder(secret) {
    return {
        getSecret: function() { return secret; },
        setSecret: function(newSecret) { secret = newSecret; }
    };
}

const box = secretHolder('initial');
console.log(box.getSecret()); // "initial"
box.setSecret('updated');
console.log(box.getSecret()); // "updated"  (the variable secret is not directly accessible)
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example interactions to guide its behavior on new inputs. By framing the request as a conversation that includes both user queries and desired assistant responses, you can steer the model toward a specific style, format, or domain knowledge without fine‑tuning. This technique works well for tasks like data extraction, code generation, or translating informal language into formal prose. The examples are placed directly in the messages array, and the final user message is the new query to be answered. Adjust the temperature and max_tokens to balance creativity and determinism.

Code example (Python, using the openai library):  

import os  
import openai  

# Load your API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt: two Q&A pairs that illustrate the desired output format  
few_shot_messages = [  
    {"role": "system", "content": "You are an expert assistant that converts informal meeting notes into concise bullet‑point summaries."},  
    {"role": "user", "content": "Notes: We talked about the Q3 budget, need to cut travel costs, and explore new vendors for cloud services."},  
    {"role": "assistant", "content": "- Q3 budget discussion\n- Reduce travel expenses\n- Evaluate new cloud‑service vendors"},  
    {"role": "user", "content": "Notes: Team agreed on weekly stand‑ups, assign John to lead the UI redesign, and postpone the beta release to next month."},  
    {"role": "assistant", "content": "- Implement weekly stand‑up meetings\n- John to lead UI redesign\n- Beta release delayed to next month"}  
]  

# New user input that the model will process using the few‑shot context  
new_query = {"role": "user", "content": "Notes: Discussed adding a dark mode feature, need to check compatibility with existing themes, and set a deadline for the next sprint."}  

# Combine the few‑shot examples with the new query  
messages = few_shot_messages + [new_query]  

# Call the Chat Completion endpoint  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",            # choose a suitable model  
    messages=messages,  
    temperature=0.2,                # low temperature for consistent formatting  
    max_tokens=150,                 # limit response length  
)  

# Print the generated summary  
print(response.choices[0].message["content"].strip())
*/

