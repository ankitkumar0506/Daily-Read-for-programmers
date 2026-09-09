<?php
// 2026-09-09 06:22:38

/* PHP
Topic: Using PDO (PHP Data Objects) for Secure Database Access

Explanation:
PDO provides a consistent interface for accessing many different databases from PHP.  
It supports prepared statements, which help prevent SQL injection attacks by separating SQL code from data.  
With PDO you can easily bind parameters, fetch results in various formats, and handle errors via exceptions.  
The connection can be configured with options such as persistent connections and error mode.  
PDO also allows you to switch database drivers (MySQL, PostgreSQL, SQLite, etc.) with minimal code changes.

Code example (MySQL connection, inserting a record, and fetching data):

<?php
// Database connection parameters
$host = 'localhost';
$db   = 'testdb';
$user = 'dbuser';
$pass = 'dbpassword';
$charset = 'utf8mb4';

// Data Source Name (DSN) tells PDO which driver to use and how to connect
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// PDO options for better error handling and performance
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return rows as associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    // Create a new PDO instance (establishes the connection)
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // If connection fails, display the error message and stop execution
    die('Connection failed: ' . $e->getMessage());
}

// ----- INSERT USING A PREPARED STATEMENT -----
$sqlInsert = "INSERT INTO users (username, email) VALUES (:username, :email)";
$stmtInsert = $pdo->prepare($sqlInsert);          // Prepare the statement once
$stmtInsert->execute([
    ':username' => 'alice',
    ':email'    => 'alice@example.com',
]); // Execute with bound values; PDO handles quoting automatically

// ----- SELECT USING A PREPARED STATEMENT -----
$sqlSelect = "SELECT id, username, email FROM users WHERE email = :email";
$stmtSelect = $pdo->prepare($sqlSelect);
$stmtSelect->execute([':email' => 'alice@example.com']);

// Fetch all matching rows (in this case likely just one)
$users = $stmtSelect->fetchAll();

foreach ($users as $user) {
    echo "ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}\n";
}
?>
*/

/* Laravel
Topic: Laravel Eloquent Mutators & Accessors

Explanation:  
Mutators allow you to modify attribute values before they are saved to the database, while accessors let you transform values when they are retrieved from a model. This is useful for formatting data such as dates, JSON, or encrypting sensitive fields automatically. Mutators and accessors are defined as methods on the Eloquent model using the setAttributeNameAttribute and getAttributeNameAttribute naming conventions. They keep data handling logic inside the model, promoting clean controllers and services. Laravel automatically calls these methods whenever you get or set the associated attribute.

Code Example (User model with password mutator and full_name accessor):

<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Fillable attributes
    protected $fillable = ['first_name', 'last_name', 'email', 'password'];

    // Mutator: hash password before saving
    public function setPasswordAttribute($value)
    {
        // Only hash if the value is not already hashed
        $this->attributes['password'] = \Hash::needsRehash($value) ? \Hash::make($value) : $value;
    }

    // Accessor: combine first and last name into full_name
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    // Example usage in a controller:
    // $user = User::create(['first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'password' => 'secret']);
    // echo $user->full_name; // Outputs "Jane Doe"
    // $user->password = 'newsecret'; // Mutator automatically hashes the new password
}
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and improves readability by allowing you to break complex queries into logical building blocks. Recursive CTEs enable hierarchical data traversal, such as organization charts or folder structures, by repeatedly applying a query to its own output. They consist of an anchor member (base case) and a recursive member that references the CTE itself. Recursive CTEs must include a termination condition to avoid infinite loops.

Code example (MySQL 8.0+):

-- Define a recursive CTE to list all sub‑ordinates of a manager
WITH RECURSIVE employee_hierarchy AS (
    -- Anchor member: start with the manager whose id is 1
    SELECT employee_id, manager_id, employee_name, 1 AS level
    FROM employees
    WHERE manager_id = 1

    UNION ALL

    -- Recursive member: find employees whose manager is in the previous level
    SELECT e.employee_id, e.manager_id, e.employee_name, eh.level + 1
    FROM employees e
    INNER JOIN employee_hierarchy eh ON e.manager_id = eh.employee_id
)
SELECT employee_id, manager_id, employee_name, level
FROM employee_hierarchy
ORDER BY level, employee_id;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing.  
The inner function retains a reference to the outer scope’s variables, forming a persistent lexical environment.  
Closures enable data encapsulation, allowing private state that cannot be accessed directly from the outside.  
They are frequently used for factories, module patterns, and event handlers that need to remember context.  
Understanding closures is essential for managing memory and avoiding common bugs like unintentionally shared state.  

Code example:  
function makeCounter(initial) {  
    let count = initial; // variable in the outer function’s scope  

    return function() { // inner function forms a closure over count  
        count += 1;  
        console.log('Current count:', count);  
    };  
}  

const counterA = makeCounter(0); // creates a separate closure  
const counterB = makeCounter(10); // another independent closure  

counterA(); // Output: Current count: 1  
counterA(); // Output: Current count: 2  
counterB(); // Output: Current count: 11  
counterB(); // Output: Current count: 12  
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI API  

Explanation:  
Few‑shot prompting lets a language model learn a new task from just a handful of examples embedded in the prompt. By carefully selecting example pairs and formatting them consistently, you guide the model to infer the desired pattern without fine‑tuning. The approach works well for classification, transformation, or extraction tasks where large labeled datasets are unavailable. Prompt length is limited by the model’s token window, so you must balance the number of examples against the input text. Using clear delimiters and explicit instructions reduces ambiguity and improves reliability across runs.  

Code example (Python, using the openai library):  

import os  
import openai  

# Set your API key – replace with your own or use environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Construct a few‑shot prompt with two labeled examples  
    prompt = (  
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"  
        "Sentence: I love the new design of the app.\n"  
        "Sentiment: Positive\n\n"  
        "Sentence: The update crashed my phone.\n"  
        "Sentiment: Negative\n\n"  
        f"Sentence: {text}\n"  
        "Sentiment:"  
    )  

    response = openai.Completion.create(  
        model="text-davinci-003",   # or any other GPT‑3.5/4 model that supports completions  
        prompt=prompt,  
        max_tokens=1,               # we only need the single-word label  
        temperature=0.0,           # deterministic output for classification  
        stop=["\n"]                 # stop at end of line to avoid extra text  
    )  

    # Extract the label from the model's reply  
    sentiment = response.choices[0].text.strip()  
    return sentiment  

# Example usage  
sample = "The customer service was okay, not great but not terrible."  
print(f"Input: {sample}")  
print("Predicted sentiment:", classify_sentiment(sample))  
*/

