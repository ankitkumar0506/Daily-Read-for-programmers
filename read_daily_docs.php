<?php
// 2026-10-30 07:32:03

/* PHP
Topic: PHP Generators (Yield)

Explanation:
- Generators provide a simple way to implement iterators without the overhead of building an entire class that implements Traversable.  
- They allow you to produce a sequence of values lazily, meaning each value is computed only when needed.  
- This can dramatically reduce memory usage when dealing with large data sets or infinite sequences.  
- The `yield` keyword pauses the function’s execution, returns a value, and resumes from the same point on the next iteration.  
- Generators are especially useful for reading large files, streaming database rows, or implementing custom loops.

Code example (with inline comments):

<?php
function getNumbers(int $start, int $end): Generator
{
    // Loop from start to end inclusive
    for ($i = $start; $i <= $end; $i++) {
        // Yield the current number and pause execution
        yield $i;
        // Execution resumes here on the next iteration
    }
}

// Using the generator
foreach (getNumbers(1, 5) as $number) {
    // Each $number is produced on demand, not stored in memory
    echo "Number: $number\n";
}
?>
*/

/* Laravel
Topic: Custom Form Request Validation in Laravel

Explanation:
- Laravel’s Form Request classes encapsulate validation logic, keeping controllers clean and focused on handling business flow.  
- By creating a dedicated request class, you can define rules, custom error messages, and authorization checks in one place.  
- The class automatically validates incoming data before the controller method is executed, returning a 422 response with errors if validation fails.  
- Custom messages allow you to tailor feedback for each rule, improving user experience and localization.  
- Using Form Requests also enables type‑hinting in controller methods, providing IDE autocomplete and static analysis benefits.

Code Example (with comments):

<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    // Determine if the user is authorized to make this request.
    public function authorize()
    {
        // Example: only allow logged‑in users.
        return auth()->check();
    }

    // Define validation rules for the incoming request data.
    public function rules()
    {
        return [
            'title'   => 'required|string|max:255',
            'body'    => 'required|string',
            'slug'    => 'required|alpha_dash|unique:articles,slug',
            'tags'    => 'nullable|array',
            'tags.*'  => 'string|distinct',
        ];
    }

    // Customize the error messages for specific validation rules.
    public function messages()
    {
        return [
            'title.required'   => 'Please provide a title for the article.',
            'slug.unique'      => 'The slug has already been taken. Choose another one.',
            'tags.*.distinct'  => 'Duplicate tags are not allowed.',
        ];
    }
}

// ---------------------------------------------------------

// In a controller, type‑hint the request to trigger automatic validation.

<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Models\Article;

class ArticleController extends Controller
{
    public function store(StoreArticleRequest $request)
    {
        // At this point, the request has already been validated.
        // Retrieve the validated data.
        $validated = $request->validated();

        // Create the article using mass assignment.
        $article = Article::create($validated);

        // Optionally attach tags if they were provided.
        if (!empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        // Return a response (could be JSON, redirect, etc.).
        return response()->json([
            'message' => 'Article created successfully.',
            'article' => $article,
        ], 201);
    }
}
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries in MySQL

Explanation:
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve readability by breaking complex queries into logical building blocks. MySQL supports both non‑recursive and recursive CTEs (since version 8.0). Recursive CTEs are useful for traversing hierarchical data such as organization charts or bill‑of‑materials. The recursion terminates when the anchor query no longer produces rows, preventing infinite loops.

Code Example (finding all sub‑ordinates of a manager using a recursive CTE):
-- Define the CTE named hierarchy that starts with the manager’s employee_id
WITH RECURSIVE hierarchy AS (
    -- Anchor member: select the manager row
    SELECT employee_id, manager_id, employee_name, 1 AS level
    FROM employees
    WHERE employee_id = 3          -- assume 3 is the top‑level manager
    UNION ALL
    -- Recursive member: find employees whose manager_id matches any employee_id already in the hierarchy
    SELECT e.employee_id, e.manager_id, e.employee_name, h.level + 1
    FROM employees e
    JOIN hierarchy h ON e.manager_id = h.employee_id
)
-- Final query: list all employees in the manager’s hierarchy ordered by level
SELECT employee_id, employee_name, level
FROM hierarchy
ORDER BY level;
*/

/* JavaScript
Topic: Async/Await Error Handling

Explanation:
Async functions let you write asynchronous code that looks synchronous, but you still need to manage errors that can occur during await operations. Using try/catch inside an async function captures both rejected promises and runtime exceptions, keeping the flow clean. You can also rethrow errors to let calling code handle them, or return fallback values when appropriate. Proper error handling prevents unhandled promise rejections, which can crash Node.js processes or hide bugs in browsers. Combining async/await with finally gives a reliable place for cleanup actions such as closing resources.

Code example:
// Simulate an asynchronous operation that may fail
function fetchData(url) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            // Randomly succeed or fail
            Math.random() > 0.5 ? resolve({data: "Success from " + url})
                               : reject(new Error("Network error"));
        }, 1000);
    });
}

// Async function with structured error handling
async function loadAndProcess(url) {
    try {
        const response = await fetchData(url);   // Await the promise
        console.log("Received:", response.data);
        // Process the data here...
        return response.data;
    } catch (err) {
        console.error("Failed to load:", err.message); // Handle the error
        // Optionally provide a default value
        return null;
    } finally {
        console.log("Cleanup actions can go here, e.g., hide loader");
    }
}

// Call the async function
loadAndProcess("https://api.example.com/resource");
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies the model with a small number of example input‑output pairs inside the prompt, guiding it to produce responses in the desired format. This technique works well for tasks like data extraction, style transfer, or custom Q&A without fine‑tuning. By carefully crafting the examples and separating them with delimiters, you can control the model’s behavior and improve consistency. The approach is lightweight, requires only an API call, and can be iterated quickly during development. It is especially useful for programmers who need ad‑hoc AI assistance within scripts or tools.  

Code example (Python, using the openai library):  

import os                                 # import needed modules  
import openai  

# Load your OpenAI API key from an environment variable  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt for converting bullet points into JSON objects  
system_prompt = "You are a helpful assistant that converts meeting notes into structured JSON."  

few_shot_examples = """\
User: Convert these notes to JSON:\n- Alice discussed budget (approved)\n- Bob raised timeline concerns\nAssistant: [{"speaker":"Alice","topic":"budget","status":"approved"},{"speaker":"Bob","topic":"timeline","status":"concern"}]\n\
User: Convert these notes to JSON:\n- Carol reported a bug in the login flow\n- Dave suggested adding a captcha\nAssistant: [{"speaker":"Carol","topic":"bug","detail":"login flow"},{"speaker":"Dave","topic":"suggestion","detail":"add captcha"}]\n\
"""  

# New request that follows the same pattern  
new_user_input = "User: Convert these notes to JSON:\n- Eva mentioned a need for documentation updates\n- Frank approved the release schedule\nAssistant:"  

# Build the full message list for the chat completion endpoint  
messages = [  
    {"role": "system", "content": system_prompt},  
    {"role": "user", "content": few_shot_examples + new_user_input}  
]  

# Call the API with temperature set low for deterministic output  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.2,  
    max_tokens=200  
)  

# Print the assistant’s JSON answer  
print(response.choices[0].message.content)   # => [{"speaker":"Eva","topic":"documentation","action":"update"},{"speaker":"Frank","topic":"release schedule","status":"approved"}]
*/

