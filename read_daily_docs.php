<?php
// 2026-10-05 07:24:47

/* PHP
Topic: PHP Generators for Memory‑Efficient Data Processing

Explanation:  
Generators allow a function to yield values one at a time instead of building a complete array in memory.  
Each call to the generator’s next() method resumes execution right after the last yield statement.  
Because only a single value is kept in memory, generators are ideal for processing large data sets or streams.  
They can be used in foreach loops just like regular arrays, simplifying iteration logic.  
Using generators can significantly reduce memory consumption and improve performance in I/O‑bound scripts.

Code example:  

<?php
// A generator that reads a large CSV file line by line
function readCsvLines(string $filePath): Generator {
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException("Unable to open file: $filePath");
    }
    // Yield each line without loading the whole file into memory
    while (($line = fgets($handle)) !== false) {
        // Trim newline characters and split by commas
        $data = str_getcsv(trim($line));
        yield $data; // Return the current row to the caller
    }
    fclose($handle);
}

// Usage: iterate over the CSV rows lazily
foreach (readCsvLines('large_dataset.csv') as $row) {
    // Process each $row here; only one row is in memory at a time
    // Example: print the first column
    echo $row[0] . PHP_EOL;
}
?>
*/

/* Laravel
Topic: Laravel Queues with Redis

Explanation:  
Laravel queues allow you to defer time‑consuming tasks such as sending emails, processing images, or generating reports to a background worker. By configuring Redis as the queue driver, you get a fast, in‑memory data store that can handle high throughput and supports multiple queue connections. Jobs are pushed onto a Redis list and workers pull them off, executing the handle method. This decouples the request lifecycle from heavy processing, improving response times and user experience. Laravel provides artisan commands to start and manage workers, and you can monitor job status via the built‑in queue dashboard or Horizon.

Code example (Job class and dispatch, plus queue configuration):

// app/Jobs/ProcessImage.php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image; // assume Intervention Image is installed

class ProcessImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $imagePath;   // path to the original image
    public $size;        // desired size e.g., [300, 200]

    // Job will be attempted up to 3 times before failing
    public $tries = 3;

    public function __construct(string $imagePath, array $size)
    {
        $this->imagePath = $imagePath;
        $this->size = $size;
    }

    // The logic that runs in the background
    public function handle()
    {
        // Open the original image
        $img = Image::make($this->imagePath);

        // Resize while maintaining aspect ratio
        $img->fit($this->size[0], $this->size[1]);

        // Save the thumbnail next to the original
        $thumbPath = dirname($this->imagePath) . '/thumb_' . basename($this->imagePath);
        $img->save($thumbPath);
    }
}

// Dispatching the job from a controller
// app/Http/Controllers/ImageController.php
public function upload(Request $request)
{
    $path = $request->file('photo')->store('photos', 'public');

    // Push a resize job onto the Redis queue named "images"
    ProcessImage::dispatch(storage_path('app/public/' . $path), [300, 200])
                 ->onQueue('images');

    return response()->json(['message' => 'Upload successful, processing in background.']);
}

// config/queue.php – set Redis as default driver
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [

    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],

],

// .env – specify Redis queue name (optional)
REDIS_QUEUE=default

// Starting a worker for the "images" queue
// Run in terminal: php artisan queue:work redis --queue=images --sleep=3 --tries=3

// Optional: using Laravel Horizon for monitoring (install horizon, then php artisan horizon) 
// Horizon UI will show the "images" queue, job throughput, and failures.
*/

/* MySQL
Topic: Recursive Common Table Expressions (CTEs) in MySQL

Explanation:  
A Recursive CTE lets you query hierarchical or graph‑structured data without needing stored procedures or temporary tables.  
The CTE is defined with the WITH RECURSIVE clause, where the first SELECT provides the anchor rows and the second SELECT references the CTE itself to produce subsequent levels.  
Each iteration adds rows until the recursive SELECT returns no new rows, at which point the query stops.  
Recursive CTEs are useful for traversing organization charts, category trees, or bill‑of‑materials structures.  
MySQL 8.0+ supports this feature, and it can be combined with other clauses like ORDER BY, LIMIT, or window functions for advanced analytics.  

Code Example (employee hierarchy):
-- Define a recursive CTE named emp_path to walk the management chain  
WITH RECURSIVE emp_path (emp_id, emp_name, manager_id, level) AS (  
    -- Anchor member: start with the top‑level manager (no manager_id)  
    SELECT emp_id, emp_name, manager_id, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL  
    UNION ALL  
    -- Recursive member: join employees to the previous level's results  
    SELECT e.emp_id, e.emp_name, e.manager_id, ep.level + 1  
    FROM employees e  
    JOIN emp_path ep ON e.manager_id = ep.emp_id  
)  
-- Final query: list all employees with their hierarchy depth, ordered by level  
SELECT emp_id, emp_name, manager_id, level  
FROM emp_path  
ORDER BY level, emp_name;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing. This allows the inner function to retain a reference to the outer scope’s variables, forming a persistent lexical environment. Closures are useful for data privacy, implementing private state, and for functions that need to be configured once and then reused. They are a core concept in functional programming patterns and event handling. Understanding closures helps avoid common pitfalls like unintended variable sharing in loops.

Code example (with comments):
function makeCounter(start) {                     // outer function receives an initial value
    let count = start;                            // count is private to the closure
    return function() {                          // inner function forms the closure
        count += 1;                               // can modify the private variable
        return count;                            // returns the updated count
    };
}
const counterA = makeCounter(0);                  // creates a new closure with its own count
console.log(counterA()); // 1                     // first call increments to 1
console.log(counterA()); // 2                     // second call increments to 2
const counterB = makeCounter(10);                 // another independent closure
console.log(counterB()); // 11                    // starts from 10, now 11
console.log(counterA()); // 3                     // counterA retains its own state, now 3
*/

/* AI
Topic: Few‑Shot Prompt Engineering with GPT‑4

Explanation:  
Few‑shot prompting supplies the model with a handful of example input‑output pairs inside the prompt, guiding it toward the desired behavior without fine‑tuning. By carefully selecting diverse yet representative examples, the model can infer the pattern and apply it to new queries. This technique works well for classification, transformation, or generation tasks where a full training set is unavailable. The prompt is constructed as a single string that concatenates the examples and the new user request. Adjusting the number and quality of examples can dramatically affect accuracy and consistency.

Code example (Python, using OpenAI’s API):

import os
import openai

# Set your OpenAI API key – replace with your own key or use an environment variable
openai.api_key = os.getenv("OPENAI_API_KEY")

# Define a few‑shot prompt for sentiment analysis
prompt = (
    "Task: Classify the sentiment of a short sentence as Positive, Negative, or Neutral.\n\n"
    "Example 1:\n"
    "Input: I love the new design of the app.\n"
    "Output: Positive\n\n"
    "Example 2:\n"
    "Input: The update caused several bugs.\n"
    "Output: Negative\n\n"
    "Example 3:\n"
    "Input: It works as expected.\n"
    "Output: Neutral\n\n"
    "Now classify the following sentence:\n"
    "Input: The customer service was helpful and quick.\n"
    "Output:"
)

response = openai.ChatCompletion.create(
    model="gpt-4o-mini",            # lightweight GPT‑4 variant suitable for prompts
    messages=[{"role": "user", "content": prompt}],
    temperature=0.0,                # deterministic output for classification
    max_tokens=10                   # limit to a short label
)

# Extract and print the model’s answer
answer = response.choices[0].message.content.strip()
print("Sentiment:", answer)
*/

