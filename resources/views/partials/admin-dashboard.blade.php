  <!-- Toolbar Section: Search, filter and sort options for bike cards -->
    <section class="toolbar-section">
            <div class="container toolbar-container">
                <div class="search-group">
                    <input type="text" class="search-input" placeholder="Search bikes...">
                </div>
            
                <input type="date" class="filter-select" name="date_stolen" title="Filter by date stolen" aria-label="Filter by date stolen">

                <select class="filter-select">
                    <option value="newest">Sort: Newest</option>
                    <option value="oldest">Sort: Oldest</option>
                </select>

            </div>
    </section>

<!-- Bike list Section -->
    <section class="bikes-section">
        <div class="container">
            <div class="bikes-section-header">
                <div class="h2">Stolen and Recovered Bikes</div>
            </div>

            <!-- If no bikes are registered, show empty state message -->
            @if($stolenBikes->isEmpty())
                <div class="bikes-empty">
                    <p>No bikes found matching your criteria.</p>
                    <p>Click <strong>+ Register New Bike</strong> to get started.</p>
                </div>

            <!-- Else loop through all bikes with a stolen or recovered status -->
           @endif

               
                
    </section>

