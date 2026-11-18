<?php
// 2026-11-18 08:51:52

/* PHP
PHP Generators  
A generator is a special kind of function that can be paused and resumed, allowing you to produce a sequence of values without building an entire array in memory.  
They use the `yield` keyword to return a value each time the generator is iterated.  
Generators are especially useful for processing large data sets, reading files line‑by‑line, or streaming results from a database.  
Because the state of the function is retained between yields, they provide an efficient way to implement lazy evaluation.  
Using a generator can significantly reduce memory consumption and improve performance in I/O‑bound scripts.  

Example – reading a large CSV file one line at a time with a generator:

<?php
// Define a generator that yields each line of a CSV file as an associative array
function readCsvGenerator(string $filePath, array $headers) : Generator {
    // Open the file for reading
    $handle = fopen($filePath, 'r');
    if ($handle === false) {
        throw new RuntimeException("Unable to open file: $filePath");
    }

    // Loop through each line until end of file
    while (($row = fgetcsv($handle)) !== false) {
        // Combine header names with row values to create an associative array
        $record = array_combine($headers, $row);
        // Yield the record back to the caller; execution pauses here
        yield $record;
    }

    // Close the file when the generator is exhausted
    fclose($handle);
}

// Usage example
$csvFile = '/path/to/large_file.csv';
$csvHeaders = ['id', 'name', 'email', 'created_at'];

foreach (readCsvGenerator($csvFile, $csvHeaders) as $row) {
    // Process each row individually – memory usage stays low
    echo "User {$row['id']}: {$row['name']} ({$row['email']})\n";
}
?>
*/

/* Laravel
Topic: Laravel Event Broadcasting with Pusher

Explanation:  
Laravel's event broadcasting allows you to push server‑side events to client browsers in real time.  
By defining an event class that implements ShouldBroadcast, Laravel will automatically queue the broadcast to the configured driver (e.g., Pusher).  
The broadcast data is serialized and sent over a secure WebSocket channel, where JavaScript listeners can react instantly.  
You can protect channels with authorization callbacks to ensure only authorized users receive the events.  
This pattern is ideal for live notifications, chat systems, or real‑time dashboards without refreshing the page.

Code example (Event class, broadcast configuration, and a simple listener in JavaScript):

// app/Events/MessageSent.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;        // data that will be sent to the client
    public $userId;         // ID of the user who sent the message

    public function __construct($message, $userId)
    {
        $this->message = $message;
        $this->userId  = $userId;
    }

    // Define the channel the event will be broadcast on
    public function broadcastOn()
    {
        // Private channel so only authenticated users can listen
        return new PrivateChannel('chat.'.$this->userId);
    }

    // Optional: customize the event name seen on the client
    public function broadcastAs()
    {
        return 'MessageSent';
    }
}

// routes/channels.php – authorize access to the private channel
Broadcast::channel('chat.{userId}', function ($user, $userId) {
    // Only allow the user to listen to their own channel
    return (int) $user->id === (int) $userId;
});

// In a controller or service, dispatch the event when a message is saved
use App\Events\MessageSent;

public function store(Request $request)
{
    $message = Message::create([
        'user_id' => auth()->id(),
        'body'    => $request->input('body')
    ]);

    // Broadcast the new message to the recipient
    event(new MessageSent($message->body, $message->recipient_id));

    return response()->json(['status' => 'Message sent']);
}

// resources/js/app.js – listen for the broadcasted event using Laravel Echo
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true,
    authEndpoint: '/broadcasting/auth'   // Laravel’s default auth route
});

// Subscribe to the private channel for the authenticated user
window.Echo.private('chat.'+Laravel.userId)
    .listen('.MessageSent', (e) => {
        console.log('New message received:', e.message);
        // Update the UI in real time, e.g., prepend the message to a chat list
    });
*/

/* MySQL
Topic: Common Table Expressions (CTEs) in MySQL  

Explanation:  
A Common Table Expression (CTE) is a temporary result set that you can reference within a SELECT, INSERT, UPDATE, or DELETE statement.  
CTEs are defined using the WITH clause and can be recursive, allowing hierarchical data to be processed efficiently.  
They improve query readability by breaking complex logic into named subqueries.  
MySQL supports both non‑recursive and recursive CTEs starting from version 8.0.  
CTEs are scoped to the statement that defines them and are not persisted beyond execution.  

Code example (calculating a hierarchical employee manager chain):  

WITH RECURSIVE manager_path AS (  
    SELECT employee_id, manager_id, employee_name, 1 AS level  
    FROM employees  
    WHERE manager_id IS NULL          -- top‑level manager (no manager)  
    UNION ALL  
    SELECT e.employee_id, e.manager_id, e.employee_name, mp.level + 1  
    FROM employees e  
    JOIN manager_path mp ON e.manager_id = mp.employee_id  
)  
SELECT employee_id, employee_name, manager_id, level  
FROM manager_path  
ORDER BY level, employee_name;   -- shows the hierarchy from top to bottom with indentation level.  
*/

/* JavaScript
Topic: Closures in JavaScript  

Explanation:  
A closure is a function that retains access to its lexical scope even after the outer function has finished executing. It allows inner functions to remember the variables from the environment in which they were created. Closures are useful for data encapsulation, creating private variables, and implementing function factories. Because the inner function holds a reference to the outer variables, those variables are not garbage‑collected until the closure itself is no longer reachable. Understanding closures is essential for mastering asynchronous code, callbacks, and module patterns in JavaScript.  

Code example:  
function makeCounter(initialValue) {                 // outer function creates a private variable  
    let count = initialValue;                       // this variable is captured by the inner function  

    return function increment(step = 1) {           // the returned function forms a closure  
        count += step;                              // it can read and modify 'count'  
        return count;                               // expose the updated value  
    };                                              // end of inner function  

}                                                   // end of outer function  

const counter = makeCounter(10); // counter now has its own private 'count'  

console.log(counter());   // 11 (default step of 1)  
console.log(counter(5));  // 16 (adds 5)  
console.log(counter());   // 17  

// Even though makeCounter has finished, the inner function still accesses 'count' via the closure.  
*/

/* AI
Topic: Few‑Shot Prompt Engineering with OpenAI’s Chat Completion API  

Explanation:  
Few‑shot prompting supplies a small set of example interactions to guide the model’s behavior without fine‑tuning. By placing a few input‑output pairs in the system or user messages, you can shape the style, format, or domain knowledge the model uses when answering. This technique works well for tasks like data extraction, code generation, or role‑playing scenarios where a full training pipeline would be overkill. The examples should be concise, representative, and cover edge cases you expect. Adjust the temperature to a low value (e.g., 0.2) for more deterministic outputs when consistency matters.  

Code example (Python, using the openai library):  

import openai  

# Set your API key – replace with your actual key or use environment variable  
openai.api_key = "sk-YOUR_API_KEY"  

# Define a few‑shot prompt: system message + example user‑assistant exchanges  
messages = [  
    {"role": "system", "content": "You are a helpful assistant that extracts product names and prices from informal text."},  
    {"role": "user", "content": "I bought a new laptop for $999 and a mouse for $25."},  
    {"role": "assistant", "content": "Laptop: $999\nMouse: $25"},  
    {"role": "user", "content": "Just got the latest smartphone, it cost me 799 dollars!"},  
    {"role": "assistant", "content": "Smartphone: $799"}  
]  

# New query that follows the same pattern  
messages.append({"role": "user", "content": "Picked up a gaming chair for $350 and a desk lamp for $45."})  

# Call the Chat Completion endpoint with low temperature for consistency  
response = openai.ChatCompletion.create(  
    model="gpt-4o-mini",  
    messages=messages,  
    temperature=0.2,  
    max_tokens=100  
)  

# Print the model’s structured extraction  
print(response.choices[0].message["content"])   # Expected output: "Gaming chair: $350\nDesk lamp: $45"
*/

