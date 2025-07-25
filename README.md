<div align="center">

# Happy Wife – Happy Life


</div>

<p align="center">Survival Guide for Modern Relationships 🆘</p>

<div align="center">

[![Support](https://img.shields.io/static/v1?style=flat-square&label=Support&message=%E2%9D%A4&logo=GitHub&color=%23fe0086)](https://boosty.to/roxblnfk)

</div>

<br />

A desktop application that helps men survive and understand relationships with women using AI-powered insights and cycle tracking. Because nothing distracts from work like family life!

**Created for an AI agent competition** to experiment with cutting-edge PHP technologies:
[BosonPHP](https://github.com/boson-php) for desktop apps,
[Symfony AI Platform](https://github.com/symfony/ai-platform) for multi-provider LLM integration,
[Spiral](https://github.com/spiral/framework) for long-running applications,
and [Cycle Active Record](https://github.com/cycle/active-record) for rapid prototyping.
The full development story and technical details are available in [this article [ru]][article-ru].

[article-ru]: https://triangular-octopus-0f6.notion.site/Symfony-AI-Platform-2395a7ab4c6c80b79909f20c30616da5

## Video Demo

[![Happy Wife - Happy Life Demo](https://img.youtube.com/vi/uAW0tnt5--4/0.jpg)](https://youtu.be/uAW0tnt5--4)

*Click to watch the survival tutorial*

## Survival Features

- **Danger Level Detection**: Real-time threat assessment (scale 1-PHP_INT_MAX)
- **Mood Prediction**: Advanced AI forecasting to avoid emotional minefields
- **Emergency Chocolate Alerts**: Critical supply warnings for high-danger periods
- **Safe Zone Calendar**: Know when it's actually safe to ask "What's wrong?"
- **Memory Backup**: Never forget important dates (your relationship depends on it!)
- **Strategic Retreat Notifications**: AI tells you when to just... disappear for a while

## Tech Stack

**Backend:**
- PHP 8.4+
- Spiral Framework
- Cycle ORM - Active Record
- SQLite for local data storage
- Symfony AI Platform for LLM integration
- BosonPHP for native desktop WebView

**Frontend:**
- Bootstrap 5 for responsive design
- HTMX for dynamic interactions

**AI Integration:**
- Multi-provider support (OpenAI, Anthropic, Google, Ollama)
- Streaming responses with context awareness
- Cycle-based mood prediction algorithms

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/roxblnfk/happy-wife-happy-life.git
   cd happy-wife-happy-life
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Start the application**
   ```bash
   # For development (web browser)
   php -S localhost:8080 app.php

   # For desktop mode
   php app.php boson:start
   ```

## Usage

The application guides users through initial configuration:

1. **Relationship Information**: Enter your name and your partner's name, relationship details
2. **AI Configuration**: Choose LLM provider and add API token
3. **Cycle Data**: Input menstrual cycle information and important dates
4. **Women's Preferences**: What she likes, dislikes, etc.

After setup, the main dashboard provides access to all features through an intuitive interface built with HTMX and PHP backend.

## The Science Behind Survival

The app uses a battle-tested algorithm to predict dangerous situations:

```php
/**
 * Relationship Survival Algorithm v2.0
 *
 * @param int<1, 28> $day Menstrual cycle day
 * @return Women Current threat level and survival requirements
 */
function getWomen(int $day): Women {
   return match(true) {
       // Code Red: Maximum Danger ⚠️
       $day <= 5 => Women::create()
           ->dangerLevel(8)
           ->mood(Mood::KernelPanic)
           ->requires('chocolate')
           ->advice('Hide in garage'),

       // Green Zone: You might actually survive 🟢
       $day == 14 => Women::create()
           ->dangerLevel(1)
           ->libido(PHP_INT_MAX)
           ->mood(Mood::ProductionReady)
           ->advice('This is your chance!'),

       // Default: Proceed with extreme caution 🟨
       default => Women::create()
           ->dangerLevel(PHP_INT_MAX)
           ->mood(Mood::BufferOverflow)
           ->requires('chocolate AND flowers AND apology')
           ->advice('Just say "Yes, dear"')
   };
}
```

*Remember: Happy Wife = Happy Life = Peaceful Code Reviews*

## Project Structure

```
app.php                  # Application entry point
app
├── config               # Configuration files
├── database             # SQLite and migrations
├── public               # Web assets
└── src
    ├── Application      # Framework-specific services
    ├── Feature          # UI features
    └── Module           # Core business logic
```

## Development Notes

**Mission Background**: Built during a 4-day emergency development sprint for an AI agent competition. The goal was to experiment with bleeding-edge PHP technologies while solving real-world survival problems.

**Technologies Tested in Combat**:
- BosonPHP (because desktop PHP apps shouldn't be a war crime)
- Symfony AI Platform (teaching AI about relationship survival)
- Spiral Framework (for applications that need to survive longer than your last argument)
- Cycle Active Record (rapid deployment when time is critical)
- HTMX (dynamic frontend without the JavaScript drama)

**Lessons Learned**: Some battles with Symfony packages lasted longer than expected. Active Record and Spiral Prototype saved the day when time was running out and technical debt was piling up like dirty dishes.

**Warning**: This project demonstrates that even engineers need survival guides for relationships. Use responsibly.
