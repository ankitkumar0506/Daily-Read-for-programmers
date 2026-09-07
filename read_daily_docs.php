<?php
// 2026-09-07 06:25:29

/* PHP
Topic: PDO Prepared Statements for Secure Database Access  

Explanation:  
- Prepared statements separate SQL code from data, preventing SQL injection attacks.  
- The PDO (PHP Data Objects) extension provides a consistent interface for many database systems.  
- Placeholders in the SQL query are bound to actual values at execution time.  
- Binding can be done by position (question marks) or by named parameters.  
- Using prepared statements also improves performance when the same query is executed multiple times with different data.  

Code example (with comments):

<?php
// Create a new PDO instance connecting to a MySQL database
$dsn = 'mysql:host=localhost;dbname=sample_db;charset=utf8mb4';
$username = 'db_user';
$password = 'db_pass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Fetch rows as associative arrays
];
$pdo = new PDO($dsn, $username, $password, $options);

// Define an SQL query with named placeholders
$sql = 'INSERT INTO users (username, email, created_at) VALUES (:username, :email, :created_at)';

// Prepare the statement once
$stmt = $pdo->prepare($sql);

// Sample data to insert
$data = [
    ':username'   => 'alice',
    ':email'      => 'alice@example.com',
    ':created_at' => date('Y-m-d H:i:s')
];

// Bind the values and execute the statement
$stmt->execute($data);

// Optionally, get the ID of the newly inserted row
$newUserId = $pdo->lastInsertId();
echo "New user inserted with ID: $newUserId";
?>
*/

/* Laravel
Topic: Custom Validation Rules in Laravel

Explanation:  
Laravel’s validator allows you to encapsulate complex validation logic in a reusable class. By implementing the Rule interface you can define a rule that can be injected wherever validation occurs. This keeps your FormRequest or controller clean and makes the rule testable in isolation. Custom rules are especially useful for checks that involve external services, database lookups, or multi‑field dependencies. Once registered, the rule can be used just like any built‑in validation rule.

Code example (app/Rules/ValidPhoneNumber.php):  
<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhoneNumber implements Rule
{
    // You may inject services via the constructor if needed
    public function __construct()
    {
        // initialization code here
    }

    // Determine if the validation rule passes.
    public function passes($attribute, $value)
    {
        // Example: allow only US phone numbers in the format (123) 456‑7890
        return preg_match('/^\(\d{3}\) \d{3}\-\d{4}$/', $value);
    }

    // Return the validation error message.
    public function message()
    {
        return 'The :attribute must be a valid US phone number (e.g., (123) 456-7890).';
    }
}
?>

Usage in a Form Request (app/Http/Requests/StoreContactRequest.php):  
<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidPhoneNumber;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true; // adjust authorization as needed
    }

    public function rules()
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            // Apply the custom rule to the phone field
            'phone' => ['required', new ValidPhoneNumber()],
        ];
    }
}
?>
*/

/* MySQL
Topic: MySQL Transactions and ACID Properties  

Explanation:  
- A transaction groups one or more SQL statements into a single unit of work that either fully succeeds or fully fails.  
- MySQL guarantees the ACID properties: Atomicity, Consistency, Isolation, and Durability, ensuring reliable data changes.  
- Transactions are started with START TRANSACTION (or BEGIN) and concluded with COMMIT to make changes permanent or ROLLBACK to undo them.  
- The default isolation level is REPEATABLE READ, which prevents non‑repeatable reads but allows phantom rows unless stricter levels are set.  
- Proper use of transactions avoids partial updates, race conditions, and maintains data integrity in multi‑user environments.  

Code example (with inline comments):  

START TRANSACTION;                     -- Begin a new transaction  
UPDATE accounts SET balance = balance - 100 WHERE account_id = 1;   -- Debit account 1  
UPDATE accounts SET balance = balance + 100 WHERE account_id = 2;   -- Credit account 2  
-- Check that both updates succeeded and balances remain non‑negative  
SELECT balance FROM accounts WHERE account_id IN (1,2);  
-- If any condition fails, undo the changes  
ROLLBACK;                              -- Undo all statements in the transaction  
-- Otherwise, make the changes permanent  
COMMIT;                                -- Commit the transaction and release locks  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:  
A closure is a function that retains access to its lexical scope even when that function is executed outside of its original context.  
Closures enable data encapsulation, allowing private variables that cannot be accessed directly from the outside.  
They are created each time a function is defined, capturing the surrounding variables at that moment.  
Common uses include factories, module patterns, and maintaining state in asynchronous callbacks.  
Understanding closures is essential for writing robust, memory‑efficient JavaScript code.

Code example with comments:
function createCounter(initialValue) {                 // outer function receives an initial value
    let count = initialValue;                         // private variable, not exposed outside
    return function increment(step = 1) {            // inner function forms a closure over 'count'
        count += step;                               // modifies the captured variable
        console.log('Current count:', count);       // can use the private state each call
        return count;                                // returns the updated value
    };
}

const counterA = createCounter(10);                    // counterA has its own private 'count'
counterA();                                            // prints "Current count: 11"
counterA(5);                                           // prints "Current count: 16"

const counterB = createCounter(0);                     // separate closure, independent state
counterB(2);                                           // prints "Current count: 2"
counterB();                                            // prints "Current count: 3"
counterA();                                            // still prints "Current count: 17" – unaffected by counterB
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs before the actual query, guiding its behavior without any fine‑tuning. By embedding these demonstrations in the message list, the model can infer the desired pattern—such as translation style, tone, or format—and apply it to new inputs. This technique works well for tasks that have clear, repeatable structure and can dramatically improve consistency. It is especially useful when building lightweight AI assistants or utilities that must adapt to user‑provided examples on the fly. The approach is model‑agnostic: any chat‑capable model (e.g., gpt‑3.5‑turbo, gpt‑4) can consume the same message format.

Code example (Python, using the OpenAI API):  

import openai  

# Insert your OpenAI API key here  
openai.api_key = "YOUR_API_KEY"  

# Define a few demonstration pairs for English‑to‑French translation  
examples = [  
    {"role": "user", "content": "Translate to French: Hello, how are you?"},  
    {"role": "assistant", "content": "Bonjour, comment ça va?"},  
    {"role": "user", "content": "Translate to French: I love programming."},  
    {"role": "assistant", "content": "J'adore la programmation."}  
]  

# New user request that follows the same pattern  
new_query = {"role": "user", "content": "Translate to French: AI is changing the world."}  

# Combine the examples with the new query into a single message list  
messages = examples + [new_query]  

# Call the chat completion endpoint, keeping temperature low for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-3.5-turbo",   # or "gpt-4" for higher quality  
    messages=messages,  
    temperature=0.2  
)  

# Extract and print the assistant’s translation  
print(response["choices"][0]["message"]["content"])  
*/

