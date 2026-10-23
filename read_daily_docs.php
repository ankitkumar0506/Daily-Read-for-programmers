<?php
// 2026-10-23 06:16:04

/* PHP
Topic: Prepared Statements with PDO

Explanation:
Prepared statements separate SQL code from data, preventing SQL injection attacks by using placeholders for user input.  
PDO (PHP Data Objects) provides a consistent interface for accessing different databases with prepared statements.  
You first prepare the SQL query once, then bind values to the placeholders each time you execute it.  
Binding can be done by name (e.g., :email) or by position (e.g., ?), offering flexibility for complex queries.  
Using prepared statements also improves performance for repeated executions of the same query.  

Code example:
<?php
// Create a new PDO connection (replace DSN, username, and password with real values)
$pdo = new PDO('mysql:host=localhost;dbname=my_database;charset=utf8mb4', 'db_user', 'db_pass');

// Enable exceptions for error handling
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Define an INSERT query with named placeholders
$sql = "INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)";

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data that might come from a form
$userData = [
    'username'      => 'johndoe',
    'email'         => 'john@example.com',
    'password_hash' => password_hash('secret123', PASSWORD_DEFAULT)
];

// Bind the values and execute the statement
$stmt->execute($userData);

// Optionally, retrieve the ID of the inserted row
$insertedId = $pdo->lastInsertId();
echo "New user created with ID: " . $insertedId;
?>
*/

/* Laravel
Laravel Service Container

The Service Container is Laravel’s powerful dependency injection tool that manages class instantiation and resolves their dependencies automatically.  
It allows you to bind interfaces to concrete classes, making your code more modular and testable.  
Bindings can be defined as singletons (one shared instance) or as regular bindings (new instance each time).  
When a class is resolved, the container reads its constructor type‑hints and injects the needed dependencies.  
Using the container directly or via the helper function app() provides a clean way to retrieve services anywhere in the application.

Example – binding an interface to an implementation and resolving it:

// Define an interface
namespace App\Contracts;
interface PaymentGateway {
    public function charge(float $amount);
}

// Implement the interface
namespace App\Services;
use App\Contracts\PaymentGateway;
class StripeGateway implements PaymentGateway {
    public function charge(float $amount) {
        // Logic to charge via Stripe API
        return "Charged $$amount using Stripe.";
    }
}

// Register the binding in a service provider (e.g., AppServiceProvider)
public function register()
{
    // Bind the interface to the concrete class
    $this->app->bind(
        \App\Contracts\PaymentGateway::class,
        \App\Services\StripeGateway::class
    );
}

// Resolve and use the service in a controller
namespace App\Http\Controllers;
use App\Contracts\PaymentGateway;

class PaymentController extends Controller
{
    protected $gateway;

    // The container injects the concrete implementation automatically
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function charge()
    {
        $result = $this->gateway->charge(99.99);
        return response($result);
    }
}

// Or resolve manually elsewhere
$gateway = app(\App\Contracts\PaymentGateway::class);
echo $gateway->charge(49.50);   // Outputs: Charged $49.5 using Stripe.  
*/

/* MySQL
Topic: Common Table Expressions (CTEs) and Recursive Queries

Explanation:
A Common Table Expression (CTE) is a temporary named result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. CTEs are defined using the WITH clause and improve readability by breaking complex queries into logical building blocks. Recursive CTEs allow you to perform hierarchical or iterative processing, such as traversing parent‑child relationships. The recursion consists of an anchor member (the base case) and a recursive member that references the CTE itself. Recursive CTEs continue to execute until the recursive member returns no rows, providing a powerful way to generate sequences or explore tree structures.

Code example with comments:
-- Create a sample table to hold an employee hierarchy
CREATE TABLE employees (
    emp_id INT PRIMARY KEY,
    emp_name VARCHAR(50),
    manager_id INT NULL
);

-- Insert sample data (emp_id, emp_name, manager_id)
INSERT INTO employees VALUES
(1, 'Alice', NULL),       -- top‑level manager
(2, 'Bob', 1),
(3, 'Carol', 1),
(4, 'David', 2),
(5, 'Eve', 2),
(6, 'Frank', 3);

-- Recursive CTE to retrieve the full reporting chain for a given employee (e.g., emp_id = 4)
WITH RECURSIVE reporting_chain AS (
    -- Anchor member: start with the employee of interest
    SELECT emp_id, emp_name, manager_id, 0 AS level
    FROM employees
    WHERE emp_id = 4

    UNION ALL

    -- Recursive member: join back to the employees table to walk up the hierarchy
    SELECT e.emp_id, e.emp_name, e.manager_id, rc.level + 1
    FROM employees e
    INNER JOIN reporting_chain rc ON e.emp_id = rc.manager_id
)
SELECT emp_id, emp_name, manager_id, level
FROM reporting_chain
ORDER BY level;

-- The result shows employee 4 (David) and each of his managers up to the top level,
-- with the 'level' column indicating the distance from the original employee.
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables from its lexical scope even after that outer function has finished executing.  
Closures enable data encapsulation, allowing private state that cannot be accessed directly from outside the function.  
They are created automatically whenever an inner function references a variable declared in an outer function.  
Because the inner function holds a reference to the outer variables, those variables are kept alive in memory.  
Closures are widely used for callbacks, event handlers, and implementing module patterns.  

Code example:  
function makeCounter(initial) {                     // outer function receives an initial value  
    let count = initial;                            // this variable is captured by the inner function  

    return function() {                            // the inner function forms a closure  
        count += 1;                                 // it can read and modify 'count'  
        console.log('Current count:', count);      // side effect: display the current count  
    };                                              // the returned function keeps access to 'count'  
}                                                   // end of makeCounter  

const counterA = makeCounter(0);  // create a counter starting at 0  
counterA();                       // prints: Current count: 1  
counterA();                       // prints: Current count: 2  

const counterB = makeCounter(10); // a separate counter with its own independent state  
counterB();                       // prints: Current count: 11  
counterA();                       // prints: Current count: 3   (counterA's state is unchanged by counterB)  
*/

/* AI
Topic: Chain‑of‑Thought Prompt Engineering with the OpenAI Chat Completion API  

Explanation:  
Chain‑of‑Thought (CoT) prompting guides a language model to break complex problems into intermediate reasoning steps, improving accuracy on multi‑step tasks. By explicitly requesting the model to “think step‑by‑step”, you encourage it to generate a logical sequence before producing the final answer. This technique works well for math, logic puzzles, and code reasoning. You can control the depth of reasoning by adjusting the prompt template and optionally limiting token usage. Combining CoT with temperature tuning yields more consistent and explainable outputs.

Code example (Python, using the openai library):
import os
import openai

# Load your API key from an environment variable or directly set it
openai.api_key = os.getenv("OPENAI_API_KEY")  # replace with your key if not using env var

def chain_of_thought(prompt, model="gpt-4o-mini", max_tokens=300):
    """
    Sends a prompt that asks the model to think step‑by‑step.
    Returns the full response containing the reasoning and final answer.
    """
    # CoT template – you can modify the wording to suit the task
    cot_prompt = (
        "You are a helpful assistant that solves problems by reasoning step‑by‑step.\n"
        "Problem: " + prompt + "\n"
        "Answer (show your reasoning before the final answer):"
    )

    response = openai.ChatCompletion.create(
        model=model,
        messages=[{"role": "user", "content": cot_prompt}],
        temperature=0.2,          # low temperature for deterministic reasoning
        max_tokens=max_tokens,
        top_p=1.0,
        n=1,
        stop=None
    )
    # Extract the assistant’s message content
    return response.choices[0].message.content.strip()

# Example usage
problem = "If a train travels 60 miles per hour for 2.5 hours, then 45 miles per hour for another 3 hours, what is the total distance?"
result = chain_of_thought(problem)
print(result)
*/

