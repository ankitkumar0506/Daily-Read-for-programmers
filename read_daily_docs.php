<?php
// 2026-10-29 07:28:46

/* PHP
PHP Topic: Generators (Yield)  

Explanation:  
- Generators allow functions to produce values one at a time without building an entire array in memory.  
- They are created using the `yield` keyword, which pauses the function’s execution and returns a value.  
- Each subsequent call to the generator resumes execution right after the last `yield`.  
- This makes them ideal for processing large data sets, streams, or infinite sequences efficiently.  
- Generators can also receive values sent back into the function using `$generator->send($value)`.  

Code example (with comments):

<?php
// A generator that yields the first $n Fibonacci numbers
function fibonacciGenerator(int $n): Generator {
    $a = 0;               // First number in the sequence
    $b = 1;               // Second number in the sequence
    for ($i = 0; $i < $n; $i++) {
        // Yield the current value of $a and pause execution
        yield $a;
        // Compute the next Fibonacci number
        $temp = $a + $b;
        $a = $b;
        $b = $temp;
    }
}

// Use the generator
foreach (fibonacciGenerator(10) as $index => $value) {
    // $index is the zero‑based position, $value is the Fibonacci number
    echo "Fibonacci #{$index}: {$value}\n";
}
?>
*/

/* Laravel
Topic: Implicit Route Model Binding in Laravel

Explanation:  
Laravel can automatically inject model instances into your route or controller based on the route parameter name. When the parameter name matches a model's primary key column, Laravel queries the database and provides the fully hydrated model. If no record is found, a 404 response is generated automatically. This feature eliminates the need for manual queries and null checks in most cases. It works for any Eloquent model and can be customized with route key names or custom binding logic.

Code example with comments:

<?php
namespace App\Http\Controllers;

use App\Models\Post;               // The Eloquent model we want to bind
use Illuminate\Http\Request;

class PostController extends Controller
{
    // The {post} placeholder will be resolved to a Post model instance
    public function show(Post $post)
    {
        // At this point $post is already a retrieved model or a 404 has been thrown
        return view('posts.show', ['post' => $post]);
    }

    // Updating a post using implicit binding
    public function update(Request $request, Post $post)
    {
        // Validate incoming data
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Fill the model with validated data and save
        $post->fill($validated);
        $post->save();

        return redirect()->route('posts.show', $post);
    }
}

// routes/web.php
use App\Http\Controllers\PostController;

// The {post} segment triggers implicit binding to the Post model
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries in MySQL

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. MySQL supports both non‑recursive and recursive CTEs starting from version 8.0. Recursive CTEs are useful for traversing hierarchical data such as organization charts or category trees. The CTE is defined using the WITH clause and can be referenced like a regular table in the main query. When using recursion, the CTE must contain an anchor member (the base case) and a recursive member that references the CTE itself, combined with UNION ALL.

Code example with comments:  

CREATE TABLE employees (  
    emp_id INT PRIMARY KEY,  
    name VARCHAR(50),  
    manager_id INT NULL   -- References emp_id of the manager; NULL for top‑level executives  
);  

INSERT INTO employees (emp_id, name, manager_id) VALUES  
(1, 'Alice', NULL),   -- CEO  
(2, 'Bob', 1),        -- Reports to Alice  
(3, 'Carol', 1),      -- Reports to Alice  
(4, 'Dave', 2),       -- Reports to Bob  
(5, 'Eve', 2);        -- Reports to Bob  

-- Recursive CTE to retrieve the full reporting hierarchy for a given manager (e.g., Alice with emp_id = 1)  
WITH RECURSIVE hierarchy AS (  
    -- Anchor member: start with the manager whose hierarchy we want  
    SELECT emp_id, name, manager_id, 0 AS level  
    FROM employees  
    WHERE emp_id = 1  
  
    UNION ALL  
  
    -- Recursive member: find employees whose manager_id matches any emp_id already in the hierarchy  
    SELECT e.emp_id, e.name, e.manager_id, h.level + 1 AS level  
    FROM employees e  
    INNER JOIN hierarchy h ON e.manager_id = h.emp_id  
)  
SELECT emp_id, name, manager_id, level  
FROM hierarchy  
ORDER BY level, emp_id;  

-- The result shows each employee under Alice, with the 'level' column indicating depth in the hierarchy.  
*/

/* JavaScript
Topic: Closures in JavaScript

Explanation:
A closure is created when an inner function accesses variables from its outer (enclosing) function after the outer function has finished executing.  
Closures allow the inner function to remember the environment in which it was created, preserving access to those variables.  
They are useful for data privacy, implementing private state, and creating factory functions.  
Because the outer variables are kept alive by the closure, they are not garbage‑collected until the inner function can no longer be reached.  
Understanding closures helps avoid common pitfalls such as unintended variable sharing in loops.

Code example with comments:
// Outer function that defines a private counter variable
function createCounter(initialValue) {
    let count = initialValue;            // This variable is private to createCounter

    // Inner function forms a closure over 'count'
    return function increment(step = 1) {
        count += step;                  // Accesses and updates the private variable
        return count;                   // Returns the updated count
    };
}

// Use the factory to create independent counters
const counterA = createCounter(0);
const counterB = createCounter(10);

console.log(counterA()); // 1  (0 + 1)
console.log(counterA(5)); // 6 (1 + 5)
console.log(counterB()); // 11 (10 + 1)
console.log(counterB(2)); // 13 (11 + 2)
*/

/* AI
Topic: Prompt Engineering for Few‑Shot Learning with the OpenAI GPT API  

Explanation:  
Few‑shot prompting lets a language model infer a task from a small set of examples embedded in the prompt. By carefully structuring the examples and the instruction, you can achieve higher accuracy without any fine‑tuning. This approach is especially useful when you have limited labeled data or need rapid prototyping. The prompt should include a clear description, a few input‑output pairs, and a blank line for the model to generate the answer. Adjusting the temperature and max tokens helps control creativity and response length.  

Code example (Python, using the openai library):  

import os  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

def classify_sentiment(text):  
    # Construct a few‑shot prompt with two labeled examples  
    prompt = (  
        "Classify the sentiment of the following sentences as Positive, Negative, or Neutral.\n\n"  
        "Sentence: I loved the new phone I bought!\n"  
        "Sentiment: Positive\n\n"  
        "Sentence: The restaurant was noisy and the food was bland.\n"  
        "Sentiment: Negative\n\n"  
        f"Sentence: {text}\n"  
        "Sentiment:"  
    )  

    response = openai.Completion.create(  
        model="gpt-3.5-turbo-instruct",   # model that supports completion style prompts  
        prompt=prompt,  
        temperature=0.0,                  # deterministic output for classification  
        max_tokens=5,                     # only need a short label  
        top_p=1.0,  
        frequency_penalty=0.0,  
        presence_penalty=0.0  
    )  

    # The model returns the sentiment label followed by a newline; strip whitespace  
    sentiment = response.choices[0].text.strip()  
    return sentiment  

# Example usage  
if __name__ == "__main__":  
    test_sentence = "The movie was okay, not great but not terrible either."  
    print(f"Input: {test_sentence}")  
    print("Predicted Sentiment:", classify_sentiment(test_sentence))  
*/

