<?php
// 2026-08-31 07:17:58

/* PHP
Topic: Using PDO (PHP Data Objects) for Secure Database Access  

Explanation:  
PDO provides a uniform interface for accessing different database systems from PHP, allowing you to switch databases with minimal code changes. It supports prepared statements, which protect against SQL injection by separating query structure from data. PDO also offers error handling modes that let you choose between silent failures, warnings, or exceptions. Connections are made through DSN strings that encapsulate driver, host, database name, and optional charset. Using PDO encourages cleaner, more maintainable code compared to the older mysql_* functions.  

Code example:  
<?php  
// Database connection parameters  
$host = 'localhost';  
$db   = 'my_database';  
$user = 'db_user';  
$pass = 'secure_password';  
$charset = 'utf8mb4';  

// DSN (Data Source Name) string specifies driver and connection info  
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";  

// PDO options: enable exceptions and set default fetch mode to associative array  
$options = [  
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  
    PDO::ATTR_EMULATE_PREPARES   => false, // use native prepared statements  
];  

try {  
    // Create a new PDO instance (establishes the connection)  
    $pdo = new PDO($dsn, $user, $pass, $options);  
} catch (PDOException $e) {  
    // Handle connection errors gracefully  
    die('Connection failed: ' . $e->getMessage());  
}  

// Example: inserting a new user with a prepared statement  
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";  
$stmt = $pdo->prepare($sql); // prepares the query without executing  

// Bind values to the named placeholders  
$stmt->bindValue(':username', 'johndoe', PDO::PARAM_STR);  
$stmt->bindValue(':email',    'john@example.com', PDO::PARAM_STR);  

// Execute the statement; data is sent separately from the SQL, preventing injection  
$stmt->execute();  

// Example: fetching rows using a prepared SELECT query  
$searchEmail = 'john@example.com';  
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = :email");  
$stmt->execute([':email' => $searchEmail]); // passing parameters as an array  

$users = $stmt->fetchAll(); // retrieves all matching rows  

foreach ($users as $user) {  
    echo "User ID: {$user['id']}, Username: {$user['username']}\n";  
}  
?>
*/

/* Laravel
Topic: Laravel Service Container & Automatic Dependency Injection

Explanation:  
The Laravel service container is a powerful tool that manages class dependencies and performs dependency injection automatically. When a class or controller type‑hints another class in its constructor, the container resolves and injects the needed instance, handling bindings, singletons, and contextual bindings behind the scenes. This eliminates manual instantiation and promotes loose coupling, making testing and maintenance easier. You can bind interfaces to concrete implementations in a service provider, allowing the container to substitute implementations without changing consuming code. The container also supports contextual bindings to resolve different implementations based on the class that requires them.

Code example (app/Http/Controllers/ReportController.php):

<?php
namespace App\Http\Controllers;

use App\Contracts\ReportGenerator;          // Interface
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportGenerator;

    // The service container automatically injects an implementation of ReportGenerator
    public function __construct(ReportGenerator $reportGenerator)
    {
        $this->reportGenerator = $reportGenerator; // Stored for later use
    }

    // Example action that uses the injected service
    public function generate(Request $request)
    {
        $type = $request->input('type', 'pdf');   // e.g., pdf or csv
        $data = $request->all();

        // The report generator handles the business logic
        $report = $this->reportGenerator->make($type, $data);

        return response($report, 200)
               ->header('Content-Type', $report->mimeType());
    }
}
?>

Code example (app/Providers/AppServiceProvider.php) – binding the interface:

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ReportGenerator;
use App\Services\PdfReportGenerator;
use App\Services\CsvReportGenerator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Default binding – PDF generator
        $this->app->bind(ReportGenerator::class, PdfReportGenerator::class);

        // Contextual binding: when CsvExportController asks for ReportGenerator,
        // give it the CSV implementation instead
        $this->app->when(\App\Http\Controllers\CsvExportController::class)
                  ->needs(ReportGenerator::class)
                  ->give(CsvReportGenerator::class);
    }
}
?>

Code example (app/Services/PdfReportGenerator.php) – concrete implementation:

<?php
namespace App\Services;

use App\Contracts\ReportGenerator;
use Illuminate\Support\Facades\Storage;

class PdfReportGenerator implements ReportGenerator
{
    // Generates a PDF report and returns a file-like object
    public function make(string $type, array $data)
    {
        // Here we would build the PDF using a library like Dompdf
        $pdfContent = '<h1>Report</h1><p>'.json_encode($data).'</p>';
        $filePath = 'reports/report_'.time().'.pdf';
        Storage::put($filePath, $pdfContent);

        return new \Illuminate\Http\File(storage_path('app/'.$filePath));
    }

    // Helper to provide correct MIME type
    public function mimeType()
    {
        return 'application/pdf';
    }
}
?>
*/

/* MySQL
Topic: Using Transactions to Ensure Data Integrity

Explanation: 
A transaction groups multiple SQL statements so they either all succeed or all fail, protecting the database from partial updates. 
MySQL supports the InnoDB storage engine for transactional control. 
BEGIN starts a transaction, COMMIT makes changes permanent, and ROLLBACK undoes them if an error occurs. 
Using transactions is essential when performing related inserts, updates, or deletes that must stay consistent. 
Proper error handling in application code should trigger a rollback when any statement within the transaction fails.

Code example:
-- Start a new transaction
BEGIN;

-- Insert a new order record
INSERT INTO orders (order_date, customer_id, total_amount) 
VALUES (NOW(), 42, 199.99);

-- Insert order items; assume order_id is auto‑generated, retrieve it with LAST_INSERT_ID()
SET @order_id = LAST_INSERT_ID();

INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
VALUES 
  (@order_id, 101, 2, 49.99),
  (@order_id, 205, 1, 99.99);

-- If everything succeeded, make the changes permanent
COMMIT;

-- If any statement had failed, you would issue:
-- ROLLBACK;   (this line would be executed in application error handling)
*/

/* JavaScript
Topic: JavaScript Closures

Explanation:
A closure is a function that retains access to the variables of its outer (enclosing) function even after that outer function has finished executing. This happens because the inner function forms a lexical environment that includes references to the outer scope's variables. Closures enable data privacy, allowing you to create private state that cannot be accessed directly from the outside. They are commonly used for function factories, partial application, and maintaining state in asynchronous callbacks. Understanding closures is essential for mastering advanced JavaScript patterns and avoiding common pitfalls like unintentionally sharing mutable state.

Code example:
// Outer function creates a private variable `counter`
function createCounter() {
    let counter = 0;                 // This variable is local to createCounter

    // Inner function forms a closure over `counter`
    return function increment(step = 1) {
        counter += step;            // Can read and modify `counter` even after createCounter returns
        return counter;
    };
}

// Use the factory to get a new counter instance
const myCounter = createCounter();

console.log(myCounter()); // 1  (default step is 1)
console.log(myCounter(5)); // 6  (adds 5)
console.log(myCounter()); // 7  (adds default 1)

// A second independent counter
const anotherCounter = createCounter();
console.log(anotherCounter()); // 1  (separate closure, its own `counter` variable)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting lets you give the model a handful of examples within the same request, steering its behavior without any fine‑tuning. By carefully formatting the user’s message you can define the task, show input‑output pairs, and then present a new query. This approach works well for classification, data extraction, and code generation where the desired pattern is simple to illustrate. The key is to keep examples concise, use clear delimiters, and maintain consistent formatting so the model can infer the rule. Adjust the temperature to 0 for deterministic results when the task requires precise matching.

Python example (requires the openai package, version 1.x):

import os
import openai

# Set your OpenAI API key (ensure it is stored securely)
openai.api_key = os.getenv("OPENAI_API_KEY")

def classify_sentiment(text):
    # Construct a few‑shot prompt with two labeled examples
    prompt = (
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"
        "Sentence: I love the new design of the app!\n"
        "Sentiment: Positive\n\n"
        "Sentence: The update caused many crashes.\n"
        "Sentiment: Negative\n\n"
        f"Sentence: {text}\n"
        "Sentiment:"
    )
    
    response = openai.ChatCompletion.create(
        model="gpt-4o-mini",          # lightweight model suitable for prompt tasks
        messages=[{"role": "user", "content": prompt}],
        temperature=0.0,              # deterministic output
        max_tokens=10,                # we only need a short label
    )
    
    # Extract the model’s answer and strip whitespace
    sentiment = response.choices[0].message.content.strip()
    return sentiment

# Example usage
print(classify_sentiment("The documentation was helpful but could be clearer."))   # Expected: Neutral or Positive depending on nuance  
*/

