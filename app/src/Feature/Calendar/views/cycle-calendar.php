<?php
/**
 * @var \Spiral\Views\ViewInterface $this
 * @var \Spiral\Router\RouterInterface $router
 * @var \App\Feature\Calendar\Internal\DTO\CalendarInfo $calendarInfo
 */
?>

<div class="card h-100" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.08);" id="cycle-calendar-widget">
    <?php
    include __DIR__ . '/cycle-calendar-content.php'; ?>
</div>

<!-- Modal for Day Details -->
<div class="modal fade" id="dayDetailsModal" tabindex="-1" aria-labelledby="dayDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dayDetailsModalLabel">Детали дня</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="dayDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
    /* Cycle Calendar Styles */
    .calendar-day {
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .calendar-day:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .calendar-day.today {
        position: relative;
        font-weight: bold;
    }

    .cycle-safe {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-color: rgba(40, 167, 69, 0.3) !important;
    }

    .cycle-caution {
        background-color: rgba(255, 193, 7, 0.1) !important;
        border-color: rgba(255, 193, 7, 0.3) !important;
    }

    .cycle-high {
        background-color: rgba(253, 126, 20, 0.1) !important;
        border-color: rgba(253, 126, 20, 0.3) !important;
    }

    .cycle-extreme {
        background-color: rgba(220, 53, 69, 0.1) !important;
        border-color: rgba(220, 53, 69, 0.3) !important;
    }

    /* HTMX loading states */
    .htmx-request.cycle-calendar-nav {
        opacity: 0.6;
        pointer-events: none;
    }

    .htmx-indicator {
        transition: opacity 0.2s ease;
    }

    /* Click animation */
    .calendar-day:active {
        transform: scale(0.95);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .calendar-day {
            min-height: 50px !important;
            padding: 0.4rem !important;
        }

        .calendar-day div {
            font-size: 0.8rem !important;
        }

        .calendar-day i {
            font-size: 0.6rem !important;
        }
    }
</style>

<script>
    // Initialize tooltips function
    function initializeTooltips() {
        // Get all tooltip triggers
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));

        // Dispose existing tooltips
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            const existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
            if (existingTooltip) {
                existingTooltip.dispose();
            }
        });

        // Initialize new tooltips
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                html: true,
                sanitize: false
            });
        });
    }

    // Function to open modal (called by HTMX onclick)
    function openDayDetailsModal() {
        const modal = new bootstrap.Modal(document.getElementById('dayDetailsModal'));
        modal.show();
    }

    // Initialize tooltips on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeTooltips);
    } else {
        initializeTooltips();
    }

    // Re-initialize tooltips after HTMX content swap
    document.addEventListener('htmx:afterSwap', function(event) {
        if (event.target.id === 'cycle-calendar-widget' || event.target.closest('#cycle-calendar-widget')) {
            // Small delay to ensure DOM is updated
            setTimeout(initializeTooltips, 10);
        }
    });
</script>
