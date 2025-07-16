# AI Chats

Problem: We cannot use WebSockets and other types of streams for AI chats.

Solution: Use HTTP requests to create and manage chat sessions, polling the server regularly to get new messages.

## Chat Mechanics

1. Creating a chat session.
User: User creates a chat session with UUID.
Backend: A chat record is created in the database in the `chat` table by UUID with status, agent and other parameters.
ORM Chat entity is described in the file `app/src/Module/Chat/Domain/Chat.php`.
Frontend: HTMX sends a request to create a chat, receives HTML code in response to display the new chat.
Endpoint for creating a chat: `POST /chat/create`.

2. Chat session.
User: sees a list of messages in the chat block.
Frontend: every 300ms sends a request to the server to get new messages, using chat UUID and UUID of the last message on endpoint `GET /chat/{uuid}/messages/[last_uuid]`.
ORM Message entity is described in the file `app/src/Module/Chat/Domain/Message.php`.
- If there are messages with "pending" status in the chat, for each such message every 300ms a request is sent to the server to get new tokens for these messages using the UUID of the message with "pending" status. Endpoint for getting tokens: `GET /chat/message/{uuid}/tokens/{position}`. Response format: `{"tokens": "some tokens", "status": "pending", "append": true, "position": 241}`.
When the `append: true` flag is set, tokens are added to the existing message text, otherwise the message text is replaced with new tokens.
The "position" parameter indicates the offset position for requesting tokens, so as not to get already received tokens. `position` should be saved for next requests to this message.

3. Sending a message.
User: User enters message text in the input field and clicks the "Send" button.
Backend: A new record is created in the `chat_message` table with chat UUID, message text and "pending" status.
A new request to LLM is created. When the LLM request starts executing, the message status is updated to "completed", a new message with "pending" status is created with the response text from LLM.
When the LLM request is completed, the handler collects all tokens into one text and saves it to the chat table with status update to "completed".

## LLM Request Mechanics

For requests, a separate `llm_request` table will be used, which will store the request UUID, request text, request status and other parameters.
After the LLM request is finished, the handler collects all tokens into one text and saves it to the chat table.
