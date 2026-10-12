<?php
// 2026-10-12 07:44:02

/* PHP
Topic: Using PDO for Secure Database Access with Prepared Statements and Transactions  

Explanation:  
PHP Data Objects (PDO) provides a consistent interface for accessing different databases. By using prepared statements, you separate SQL logic from data values, which protects against SQL injection attacks. PDO also supports transactions, allowing multiple queries to be executed atomically—either all succeed or all are rolled back. Errors can be handled with exceptions, making debugging clearer. The same PDO code can be reused with MySQL, PostgreSQL, SQLite, and other supported databases, improving portability.

Code example (PHP):

<?php
// Enable exception handling for PDO
$dsn = 'mysql:host=localhost;dbname=shop;charset=utf8mb4';
$username = 'db_user';
$password = 'secure_pass';

try {
    // Create a new PDO instance with error mode set to exceptions
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Begin a transaction
    $pdo->beginTransaction();

    // Prepare an INSERT statement with placeholders
    $stmt = $pdo->prepare(
        'INSERT INTO orders (user_id, total, status) VALUES (:user_id, :total, :status)'
    );

    // Bind values and execute the statement
    $stmt->execute([
        ':user_id' => 42,
        ':total'   => 199.99,
        ':status'  => 'pending',
    ]);

    // Get the ID of the newly inserted order
    $orderId = $pdo->lastInsertId();

    // Prepare a second INSERT for order items, using the order ID
    $itemStmt = $pdo->prepare(
        'INSERT INTO order_items (order_id, product_id, quantity, price) 
         VALUES (:order_id, :product_id, :quantity, :price)'
    );

    // Example items array
    $items = [
        ['product_id' => 7,  'quantity' => 2, 'price' => 49.99],
        ['product_id' => 12, 'quantity' => 1, 'price' => 99.99],
    ];

    // Insert each item
    foreach ($items as $item) {
        $itemStmt->execute([
            ':order_id'   => $orderId,
            ':product_id' => $item['product_id'],
            ':quantity'   => $item['quantity'],
            ':price'      => $item['price'],
        ]);
    }

    // Commit the transaction if all queries succeeded
    $pdo->commit();
    echo "Order $orderId has been saved successfully.";
} catch (Exception $e) {
    // Roll back the transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Log or display the error message
    echo "Failed to save order: " . $e->getMessage();
}
?>
*/

/* Laravel
Topic: Laravel Service Container – Binding Interfaces to Implementations

Explanation:  
The Laravel service container is a powerful tool for managing class dependencies and performing dependency injection. By binding an interface to a concrete class, you tell the container which implementation to resolve when the interface is type‑hinted. This promotes loose coupling and makes testing easier, as you can swap implementations without changing the consuming code. Bindings are typically defined in a service provider’s register method. Once bound, you can resolve the interface directly from the container or let Laravel inject it automatically into controllers, jobs, or other classes.

Code example (App/Providers/AppServiceProvider.php):
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\PaymentGateway;
use App\Services\StripePaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the PaymentGateway interface to the StripePaymentGateway implementation
        $this->app->bind(PaymentGateway::class, function ($app) {
            // You can pull configuration values or other services here
            $apiKey = config('services.stripe.secret');
            return new StripePaymentGateway($apiKey);
        });
    }

    public function boot()
    {
        //
    }
}
?>

Usage in a controller (App/Http/Controllers/OrderController.php):
<?php
namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $paymentGateway;

    // Laravel automatically injects the bound implementation
    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store(Request $request)
    {
        $amount = $request->input('amount');

        // Use the injected payment gateway to process the payment
        $this->paymentGateway->charge($amount);

        return response()->json(['status' => 'payment processed']);
    }
}
?>
*/

/* MySQL
Topic: MySQL Transactions and ACID Guarantees

Explanation: 
A transaction groups one or more SQL statements into a single logical unit of work that either fully succeeds or fully fails. 
It ensures the ACID properties: Atomicity (all-or-nothing), Consistency (database remains valid), Isolation (concurrent transactions don’t interfere), and Durability (committed changes survive failures). 
Using transactions you can protect critical operations such as money transfers, inventory updates, or batch inserts from partial execution. 
You explicitly begin a transaction, execute the statements, and then either COMMIT to make changes permanent or ROLLBACK to discard them. 
If an error occurs, rolling back restores the database to its state before the transaction started.

Code example (with comments):

-- Start a new transaction
START TRANSACTION;

-- Debit $100 from account A
UPDATE accounts SET balance = balance - 100 WHERE account_id = 1;

-- Credit $100 to account B
UPDATE accounts SET balance = balance + 100 WHERE account_id = 2;

-- Check that both updates affected exactly one row each
-- (In a real application you would verify the row count in application code)
SELECT ROW_COUNT() INTO @rows_affected;
IF @rows_affected <> 2 THEN
    -- Something went wrong, undo the changes
    ROLLBACK;
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Transfer failed, transaction rolled back';
ELSE
    -- All good, make the changes permanent
    COMMIT;
END IF;
*/

/* JavaScript
Topic: Async/Await with Robust Error Handling  

Explanation:  
- Async/Await lets you write asynchronous code that looks and behaves like synchronous code, improving readability.  
- An async function always returns a promise; the await keyword pauses execution until the awaited promise resolves or rejects.  
- Proper error handling requires wrapping await calls in try/catch blocks, because a rejected promise throws an exception inside the async function.  
- You can re‑throw errors or convert them into custom error objects to provide more context to callers.  
- Combining multiple await calls inside a single try block lets you handle related failures together, while separate blocks isolate independent operations.  

Code example (plain JavaScript with comments):  
function fetchJson(url) {  
    return fetch(url)  
        .then(response => {  
            if (!response.ok) {  
                throw new Error(`HTTP ${response.status} - ${response.statusText}`);  
            }  
            return response.json();  
        });  
}  

async function loadUserData(userId) {  
    const endpoint = `https://api.example.com/users/${userId}`;  

    try {  
        // Await the promise returned by fetchJson; if it rejects, control jumps to catch  
        const user = await fetchJson(endpoint);  

        // Additional asynchronous operation, e.g., fetching the user's posts  
        const posts = await fetchJson(`${endpoint}/posts`);  

        return { user, posts }; // Successful result  
    } catch (err) {  
        // Log the original error and wrap it in a more descriptive one  
        console.error('Failed to load user data:', err);  
        throw new Error(`loadUserData failed for ID ${userId}: ${err.message}`);  
    }  
}  

// Example usage  
loadUserData(42)  
    .then(data => console.log('User data:', data))  
    .catch(err => console.error('Error:', err.message));
*/

/* AI
Topic: Few‑Shot Prompt Engineering for GPT‑style Language Models  

Explanation:  
1. Few‑shot prompting supplies a small number of example input‑output pairs inside the prompt, guiding the model to perform a new task without additional fine‑tuning.  
2. The examples act as in‑context demonstrations, helping the model infer the desired format, style, and reasoning pattern.  
3. Selecting diverse and representative examples improves generalization, while keeping the total token count within the model’s context window.  
4. You can programmatically build prompts, insert user queries, and parse the model’s response for downstream use.  
5. This technique is especially useful for tasks like classification, extraction, or transformation where labeled data are scarce.  

Code example (Python, using OpenAI’s chat completion API):

import os
import json
import openai

# Load your API key from an environment variable or a config file
openai.api_key = os.getenv("OPENAI_API_KEY")

def build_few_shot_prompt(user_question):
    """
    Construct a prompt that includes two demonstration examples
    and then appends the user's question.
    """
    # Example 1: converting a sentence to title case
    example1 = (
        "User: Convert the following sentence to title case:\n"
        "\"the quick brown fox jumps over the lazy dog.\"\n"
        "Assistant: The Quick Brown Fox Jumps Over The Lazy Dog."
    )
    # Example 2: extracting a date from a sentence
    example2 = (
        "User: Extract the date from this text:\n"
        "\"Our meeting is scheduled for 12th March 2024 at 3 PM.\"\n"
        "Assistant: 12 March 2024"
    )
    # Combine the examples with the new request
    prompt = f"{example1}\n\n{example2}\n\nUser: {user_question}\nAssistant:"
    return prompt

def ask_gpt(user_question):
    prompt = build_few_shot_prompt(user_question)
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",            # Choose a model that supports chat completions
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,                # Deterministic output for extraction tasks
        max_tokens=200
    )
    # The assistant's reply is in the first choice's message content
    answer = response.choices[0].message.content.strip()
    return answer

# Example usage
if __name__ == "__main__":
    question = "Summarize the following paragraph in one sentence:\n\"Artificial intelligence has transformed many industries, enabling automation, predictive analytics, and new forms of human‑computer interaction.\"\n"
    result = ask_gpt(question)
    print("Model response:", result)
*/

