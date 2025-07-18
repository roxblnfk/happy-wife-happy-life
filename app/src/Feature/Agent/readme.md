# Agent System Overview

This document provides a summary of all available AI agents in the "Happy Wife - Happy Life" application. Each agent is specialized to help men understand and build better relationships with their female partners.

## Agent Categories

The agents are organized into 4 main categories based on their purpose and functionality:

### 1. Relationship Agents (`/Relationship/`)
These agents help with daily communication and relationship building:

- **ApologyExpertAgent** (`ApologyExpertAgent.php`)  
  *Purpose*: Provides guidance on how to apologize properly and sincerely when mistakes happen

- **ComplimentMasterAgent** (`ComplimentMasterAgent.php`)  
  *Purpose*: Helps create meaningful compliments and positive communication

- **DisputeMediatorAgent** (`DisputeMediatorAgent.php`)  
  *Purpose*: Assists in resolving disagreements and finding common ground

- **RomanticPlannerAgent** (`RomanticPlannerAgent.php`)  
  *Purpose*: Plans romantic activities, dates, and special moments for couples

- **SignalTranslatorAgent** (`SignalTranslatorAgent.php`)  
  *Purpose*: Decodes female non-verbal cues, hints, and indirect communication patterns

### 2. Crisis Management Agents (`/Crisis/`)
These agents provide emergency support during relationship difficulties:

- **CoupleTherapistAgent** (`CoupleTherapistAgent.php`)  
  *Purpose*: Offers therapeutic guidance for serious relationship issues and long-term problems

- **CrisisManagerAgent** (`CrisisManagerAgent.php`)  
  *Purpose*: Provides immediate help during relationship emergencies and high-stress situations

- **DiplomatAgent** (`DiplomatAgent.php`)  
  *Purpose*: Facilitates peaceful negotiations and conflict resolution

- **SOSConsultantAgent** (`SOSConsultantAgent.php`)  
  *Purpose*: Emergency consultant for urgent relationship situations requiring immediate action

### 3. Care & Support Agents (`/Care/`)
These agents focus on showing care and attention to the female partner:

- **CulinaryHelperAgent** (`CulinaryHelperAgent.php`)  
  *Purpose*: Suggests recipes and cooking ideas based on mood, cycle phase, and preferences

- **GiftGuideAgent** (`GiftGuideAgent.php`)  
  *Purpose*: Helps choose appropriate gifts for different occasions and moods

- **SurprisePlannerAgent** (`SurprisePlannerAgent.php`)  
  *Purpose*: Plans surprises and unexpected pleasant moments

### 4. Planning Agents (`/Planning/`)
These agents help organize future events and manage practical aspects:

- **BudgetConsultantAgent** (`BudgetConsultantAgent.php`)  
  *Purpose*: Provides financial advice for couple expenses and relationship investments

- **EventManagerAgent** (`EventManagerAgent.php`)  
  *Purpose*: Organizes family events, celebrations, and special occasions

- **VacationPlannerAgent** (`VacationPlannerAgent.php`)  
  *Purpose*: Plans trips, vacations, and travel experiences for couples

## Agent Features

### Common Functionality
All agents share these core features:
- Chat-based interaction through the ChatAgent interface
- System prompts tailored to their specific expertise
- Integration with the main chat system
- Greeting messages in Russian
- Bootstrap icons for visual identification

### Specialized Knowledge
Each agent includes:
- **Menstrual cycle awareness**: Understanding how hormonal changes affect mood and needs
- **Emotional intelligence**: Recognizing and responding to emotional states
- **Cultural sensitivity**: Considering cultural and personal preferences
- **Practical advice**: Providing actionable recommendations and step-by-step guidance

### Integration Points
- **Calendar system**: EventManagerAgent integrates with calendar events
- **User data**: All agents can access partner profile information
- **Chat history**: Agents can reference previous conversations
- **Emergency escalation**: Crisis agents can recommend professional help when needed

## Usage Patterns

### Quick Help
Most agents provide immediate assistance for common relationship situations through their greeting messages and initial prompts.

### Deep Consultation
For complex issues, agents can engage in extended conversations to understand context and provide personalized advice.

### Preventive Guidance
Many agents focus on preventing problems before they occur by teaching relationship skills and awareness.

### Emergency Response
Crisis category agents are designed for urgent situations requiring immediate intervention and damage control.

## Technical Implementation

All agents implement the `ChatAgent` interface and follow these patterns:
- `getCard()`: Returns agent metadata (name, description, icon, color)
- `chatInit()`: Sets up system prompt and sends greeting message
- `chatProcess()`: Handles ongoing conversation logic
- `canHandle()`: Determines if agent can process specific chat types

The agent system integrates with the broader application through the Chat module and provides specialized AI assistance for relationship management.