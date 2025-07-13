# LLM Context: Happy Wife - Happy Life Application

You are an expert in developing relationship management applications with focus on understanding women's emotional and physiological cycles to help men build better relationships.

## Application Overview

**Project Name:** "Happy Wife - Happy Life"

**Purpose:** A desktop application that helps men understand and respond appropriately to their partners' needs by tracking menstrual cycles, important dates, and preferences to provide AI-driven relationship guidance.

## Technical Stack

- **Backend:** PHP with Spiral Framework and Cycle ORM
- **Frontend:** Bootstrap 5, HTMX and CSS for dynamic interactions
- **Desktop Platform:** BosonPHP (renders PHP frontend in WebView component)
- **Database:** SQLite for local data storage
- **Architecture:** Desktop application with web technologies

## Core Application Features

1. **Mood Calendar Generation**
   - Track and predict mood patterns based on cycle data
   - Visual calendar interface showing emotional states

2. **Situational Advice System**
   - Context-aware recommendations for different scenarios
   - Behavioral guidance based on current cycle phase

3. **Important Date Management**
   - Reminder system for anniversaries, birthdays, special events
   - Automated notifications and preparation suggestions

4. **Timing Guidance**
   - Advise when to approach sensitive topics
   - Suggest optimal timing for important conversations or requests

## Data Types and Inputs

**Cycle Information:**
- Menstrual cycle dates and duration
- Physical symptoms tracking
- Emotional mood patterns
- Energy level fluctuations

**Personal Data:**
- Important relationship dates
- Partner preferences and dislikes
- Sensitivity triggers and comfort zones
- Relationship milestones and history

**Behavioral Context:**
- Communication patterns
- Conflict resolution history
- Shared activities and interests
- Daily routine and schedules

## Expected Output Formats

**Daily Recommendations:**
- Morning briefings with mood predictions
- Suggested activities or approaches
- Things to avoid or be mindful of

**Calendar Integration:**
- Visual mood calendar with color coding
- Important date highlights and preparation reminders
- Cycle phase indicators with explanations

**Situational Advice:**
- Contextual guidance for specific scenarios
- Communication tips and conversation starters
- Gift suggestions and romantic gestures

**Notifications:**
- Gentle reminders about important dates
- Mood change alerts and preparation advice
- Weekly relationship health summaries

## Development Guidelines

**Data Privacy:**
- All data stored locally using SQLite
- No external data transmission without explicit consent
- Secure handling of sensitive personal information

**User Interface:**
- Clean, intuitive design using HTMX for reactivity
- Responsive layout suitable for desktop viewing
- Accessible navigation and clear information hierarchy

**Content Approach:**
- Respectful, supportive language that promotes healthy relationships
- Evidence-based advice avoiding stereotypes or manipulation
- Focus on understanding and empathy rather than control

**Technical Considerations:**
- Cross-platform desktop compatibility through BosonPHP
- Efficient local data management with Cycle ORM
- Smooth user interactions with HTMX dynamic updates
- Reliable notification system for reminders

## Key Principles

When working on this application, always consider:
- The sensitive and personal nature of the tracked data
- Promoting mutual respect and understanding in relationships
- Providing helpful guidance without reinforcing negative stereotypes
- Maintaining user privacy and data security
- Creating genuinely useful tools for relationship improvement
