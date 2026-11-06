<?php
// 2026-11-06 08:18:06

/* PHP
Topic: PDO Prepared Statements for Secure Database Queries

Explanation:
Prepared statements separate SQL code from data, preventing malicious input from altering the query structure.  
Using PDO (PHP Data Objects) ensures a consistent interface across different database systems.  
Placeholders are used in the SQL, and actual values are bound later, which also improves performance for repeated queries.  
If an error occurs, PDO can throw exceptions, making debugging easier.  
This method protects against SQL injection and promotes clean, maintainable code.  

Code Example:
// Connect to the database using PDO
$dsn = 'mysql:host=localhost;dbname=testdb;charset=utf8mb4';
$username = 'dbuser';
$password = 'dbpass';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Prepare an INSERT statement with named placeholders
$sql = "INSERT INTO users (username, email, created_at) VALUES (:username, :email, NOW())";
$stmt = $pdo->prepare($sql);

// Bind values to the placeholders
$stmt->bindValue(':username', $newUsername, PDO::PARAM_STR);
$stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);

// Execute the statement
if ($stmt->execute()) {
    echo "New user added with ID: " . $pdo->lastInsertId();
} else {
    echo "Failed to add new user.";
}
*/

/* Laravel
Topic: Laravel Eloquent Polymorphic Relationships

Explanation:  
Polymorphic relationships allow a model to belong to more than one other model on a single association. This is useful when different models share a common relationship, such as comments that can belong to posts, videos, or articles. Laravel handles the underlying foreign key and type columns automatically, simplifying queries across the related models. You define a morphTo method on the child model and a morphMany or morphOne on each parent model. The database tables need a nullable id column (e.g., commentable_id) and a string column for the type (e.g., commentable_type).

Code example (PHP with inline comments):

<?php
// In the Comment model
class Comment extends Model
{
    // This tells Laravel that Comment can belong to any model that is "commentable"
    public function commentable()
    {
        return $this->morphTo(); // resolves to the parent model based on commentable_type and commentable_id
    }
}

// In the Post model
class Post extends Model
{
    // A Post can have many comments, using the same polymorphic table
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable'); // 'commentable' is the base name of the relation
    }
}

// In the Video model
class Video extends Model
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

// Example usage in a controller or tinker
$post = Post::find(1);
$post->comments()->create(['body' => 'Great post!']); // automatically sets commentable_type = 'App\Models\Post' and commentable_id = 1

$video = Video::find(5);
$comment = new Comment(['body' => 'Nice video!']);
$video->comments()->save($comment); // same automatic handling for type and id

// Retrieving the parent of a comment
$comment = Comment::find(10);
$parent = $comment->commentable; // returns the Post or Video instance that owns the comment
?>
*/

/* MySQL
Topic: Common Table Expressions (CTE) and Recursive Queries

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement. It is defined using the WITH clause and can improve readability by breaking complex queries into logical building blocks. MySQL 8.0+ also supports recursive CTEs, which allow you to traverse hierarchical data such as organizational charts or bill‑of‑materials structures. Recursive CTEs consist of an anchor member (the starting rows) and a recursive member that repeatedly references the CTE itself until a termination condition is met. They are evaluated row‑by‑row, making them suitable for generating sequences, path enumerations, or performing graph traversals.

Code Example (calculating a simple employee hierarchy):
-- Define a recursive CTE named org_chart
WITH RECURSIVE org_chart AS (
    -- Anchor member: select top‑level manager(s)
    SELECT 
        employee_id,
        manager_id,
        employee_name,
        1 AS level
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive member: join each employee to their manager
    SELECT 
        e.employee_id,
        e.manager_id,
        e.employee_name,
        oc.level + 1 AS level
    FROM employees e
    INNER JOIN org_chart oc ON e.manager_id = oc.employee_id
)
-- Query the hierarchical result
SELECT 
    employee_id,
    manager_id,
    employee_name,
    level
FROM org_chart
ORDER BY level, manager_id;
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to the variables of its lexical scope even after that outer function has finished executing. This allows inner functions to remember and manipulate data from the surrounding environment, enabling patterns like data privacy and function factories. Closures are created automatically whenever a function is defined inside another function. They are fundamental for asynchronous programming, module patterns, and creating partially applied functions. Understanding closures helps avoid common pitfalls such as unintentionally sharing mutable state across invocations.

Code Example:  
// A simple counter factory using a closure  
function createCounter(initialValue) {  
    let count = initialValue; // 'count' is private to the closure  

    // The inner function forms a closure over 'count'  
    return function() {  
        count += 1;               // It can read and modify 'count'  
        return count;            // Returns the updated count  
    };  
}  

// Create two independent counters  
const counterA = createCounter(0);  
const counterB = createCounter(10);  

console.log(counterA()); // 1  
console.log(counterA()); // 2  
console.log(counterB()); // 11  
console.log(counterB()); // 12  

// The private 'count' variable cannot be accessed directly  
// console.log(counterA.count); // undefined – encapsulation achieved through the closure.
*/

/* AI
Topic: Few‑Shot Prompt Engineering for Large Language Models  

Explanation:  
Few‑shot prompting supplies a language model with a small number of example input‑output pairs directly in the prompt, guiding the model toward the desired format and reasoning style.  
It works without fine‑tuning, making it fast to iterate and adaptable to many tasks such as classification, translation, or code generation.  
The key is to choose clear, representative examples and to separate them consistently from the new query.  
Careful ordering and the use of delimiters (e.g., "---") help the model distinguish examples from the actual request.  
Performance often improves when examples cover edge cases and the prompt ends with an explicit instruction for the model to respond.  

Code example (Python, OpenAI API) with comments:  

import os  
import openai  

# Set your API key (ensure it is stored securely)  
openai.api_key = os.getenv("OPENAI_API_KEY")  

# Define a few‑shot prompt for converting natural language to SQL  
prompt = """\
Convert the following natural‑language request into a SQL query.  

Example 1:  
Question: List the names of employees hired after 2020.  
SQL: SELECT name FROM employees WHERE hire_date > '2020-12-31';  

Example 2:  
Question: How many orders were placed in January?  
SQL: SELECT COUNT(*) FROM orders WHERE MONTH(order_date) = 1;  

---  

Question: {user_question}  
SQL:"""

def generate_sql(user_question: str) -> str:  
    # Insert the user's question into the prompt template  
    filled_prompt = prompt.format(user_question=user_question)  

    # Call the chat completion endpoint with a system message that sets the role  
    response = openai.ChatCompletion.create(  
        model="gpt-4o-mini",  
        messages=[  
            {"role": "system", "content": "You are a helpful assistant that translates English to SQL."},  
            {"role": "user", "content": filled_prompt}  
        ],  
        temperature=0.0,          # deterministic output for code generation  
        max_tokens=150,  
    )  

    # Extract the generated SQL from the assistant's reply  
    sql = response.choices[0].message.content.strip()  
    return sql  

# Example usage  
question = "Show the total revenue for each product category in 2023."  
print(generate_sql(question))  
*/

