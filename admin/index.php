<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Restaurant - Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .nav-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .nav-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .nav-btn:hover {
            background-color: #0056b3;
        }

        .nav-btn.active {
            background-color: #28a745;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: middle;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 60px;
        }

        .status.pending { background-color: #ffc107; color: #000; }
        .status.confirmed { background-color: #28a745; color: #fff; }
        .status.preparing { background-color: #fd7e14; color: #fff; }
        .status.ready { background-color: #17a2b8; color: #fff; }
        .status.delivered { background-color: #28a745; color: #fff; }
        .status.cancelled { background-color: #dc3545; color: #fff; }

        .action-btn {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            white-space: nowrap;
            display: inline-block;
        }

        .action-btn.confirm { background-color: #28a745; color: white; }
        .action-btn.cancel { background-color: #dc3545; color: white; }
        .action-btn.approve { background-color: #007bff; color: white; }
        .action-btn.reject { background-color: #6c757d; color: white; }
        .action-btn.delete { background-color: #e74c3c; color: white; }

        /* Enhanced table layout for better organization */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }

        .admin-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            padding: 12px 8px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            white-space: nowrap;
        }

        .admin-table td {
            padding: 12px 8px;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
            word-wrap: break-word;
        }

        /* Column width specifications for reviews table */
        .reviews-table th:nth-child(1) { width: 8%; }  /* ID */
        .reviews-table th:nth-child(2) { width: 15%; } /* Customer */
        .reviews-table th:nth-child(3) { width: 12%; } /* Rating */
        .reviews-table th:nth-child(4) { width: 35%; } /* Review Text */
        .reviews-table th:nth-child(5) { width: 10%; } /* Status */
        .reviews-table th:nth-child(6) { width: 12%; } /* Date */
        .reviews-table th:nth-child(7) { width: 8%; }  /* Actions */

        /* Review text column - allow wrapping */
        .reviews-table td:nth-child(4) {
            white-space: normal;
            max-width: 300px;
            overflow-wrap: break-word;
            word-break: break-word;
            line-height: 1.4;
        }

        /* Actions column styling */
        .reviews-table td:nth-child(7) {
            text-align: center;
            white-space: nowrap;
            min-width: 120px;
        }

        /* Better action button spacing and layout */
        .action-btn {
            display: inline-block;
            margin: 2px 1px;
            padding: 6px 10px;
            font-size: 11px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .action-btn:hover {
            opacity: 0.8;
        }

        /* Action button container for better spacing */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            justify-content: center;
            align-items: center;
        }

        /* Responsive table wrapper */
        .table-wrapper {
            overflow-x: auto;
            margin: 15px 0;
            border: 1px solid #dee2e6;
            border-radius: 8px;
        }

        /* Row hover effect */
        .admin-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Status badge improvements */
        .status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 60px;
        }

        /* Specific table column widths for different tables */
        
        /* Orders table column widths */
        .orders-table th:nth-child(1) { width: 8%; }  /* ID */
        .orders-table th:nth-child(2) { width: 18%; } /* Customer */
        .orders-table th:nth-child(3) { width: 10%; } /* Type */
        .orders-table th:nth-child(4) { width: 15%; } /* Items */
        .orders-table th:nth-child(5) { width: 12%; } /* Total */
        .orders-table th:nth-child(6) { width: 10%; } /* Status */
        .orders-table th:nth-child(7) { width: 12%; } /* Date */
        .orders-table th:nth-child(8) { width: 15%; } /* Actions */

        /* Menu table column widths */
        .menu-table th:nth-child(1) { width: 8%; }  /* ID */
        .menu-table th:nth-child(2) { width: 30%; } /* Name */
        .menu-table th:nth-child(3) { width: 15%; } /* Category */
        .menu-table th:nth-child(4) { width: 12%; } /* Price */
        .menu-table th:nth-child(5) { width: 10%; } /* Available */
        .menu-table th:nth-child(6) { width: 25%; } /* Actions */

        /* Reservations table column widths */
        .reservations-table th:nth-child(1) { width: 8%; }  /* ID */
        .reservations-table th:nth-child(2) { width: 18%; } /* Name */
        .reservations-table th:nth-child(3) { width: 20%; } /* Email */
        .reservations-table th:nth-child(4) { width: 12%; } /* Date */
        .reservations-table th:nth-child(5) { width: 10%; } /* Time */
        .reservations-table th:nth-child(6) { width: 8%; }  /* Guests */
        .reservations-table th:nth-child(7) { width: 10%; } /* Status */
        .reservations-table th:nth-child(8) { width: 14%; } /* Actions */

        /* Make customer names and important text bold */
        .admin-table td strong {
            font-weight: 600;
            color: #2c3e50;
        }

        /* Improve mobile responsiveness */
        @media (max-width: 768px) {
            .admin-table {
                font-size: 12px;
            }
            
            .action-btn {
                font-size: 10px;
                padding: 4px 6px;
                margin: 1px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 2px;
            }
            
            .reviews-table td:nth-child(4) {
                max-width: 150px;
            }
        }

        /* Loading state for tables */
        .table-loading {
            text-align: center;
            padding: 40px 20px;
            color: #666;
            font-style: italic;
        }

        /* Empty state for tables */
        .table-empty {
            text-align: center;
            padding: 40px 20px;
            color: #888;
        }

        .table-empty h3 {
            color: #666;
            margin-bottom: 10px;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            background-color: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #333;
        }

        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        /* Form Styles */
        #menuItemForm {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .form-group label input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        /* Logout button */
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
        
        /* Overview Stats Styles */
        .section-title {
            font-size: 24px;
            margin-bottom: 5px;
            color: #34495e;
        }
        
        .section-subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 25px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .stat-icon {
            font-size: 32px;
            margin-bottom: 10px;
            opacity: 0.8;
        }
        
        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .stat-label {
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        /* Loading and error state styles */
        .loading-pulse {
            display: inline-block;
            animation: pulse 1.5s infinite ease-in-out;
            color: #7f8c8d;
        }
        
        .error-state {
            display: inline-block;
            color: #e74c3c;
            font-size: 16px;
        }
        
        /* Animation for fresh data indication */
        .stat-card.updated {
            animation: highlight 1s ease-in-out;
        }
        
        @keyframes pulse {
            0% { opacity: 0.5; }
            50% { opacity: 1; }
            100% { opacity: 0.5; }
        }
        
        @keyframes highlight {
            0% { background-color: rgba(46, 204, 113, 0.2); }
            100% { background-color: white; }
        }
        
        /* Menu Statistics Styling */
        .menu-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .menu-stats div {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .menu-stats div:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .menu-stats h4 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
            color: #2c3e50;
        }
        
        .menu-stats p {
            margin: 0;
            font-size: 14px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        /* No data and error state styling */
        .no-data {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .no-data-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.6;
        }
        
        .no-data h3 {
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        
        .no-data p {
            color: #7f8c8d;
            margin: 0 0 25px 0;
            line-height: 1.5;
        }
        
        .no-data .action-btn {
            background: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease;
        }
        
        .no-data .action-btn:hover {
            background: #2980b9;
        }

        /* Enhanced Error Card Styles */
        .error-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            margin: 30px 0;
            overflow: hidden;
            border: 1px solid #e9ecef;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .error-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }

        .error-card-content {
            padding: 40px 30px;
            text-align: center;
        }

        .error-icon {
            margin-bottom: 25px;
        }

        .error-icon-circle {
            display: inline-block;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            border-radius: 50%;
            line-height: 80px;
            font-size: 36px;
            box-shadow: 0 4px 15px rgba(253, 203, 110, 0.3);
            animation: pulse-warning 2s infinite;
        }

        @keyframes pulse-warning {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .error-details {
            margin-bottom: 30px;
        }

        .error-title {
            color: #2d3436;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 15px 0;
            letter-spacing: -0.5px;
        }

        .error-message {
            color: #636e72;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 25px 0;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .error-suggestions {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            border-left: 4px solid #74b9ff;
        }

        .suggestion-title {
            font-weight: 600;
            color: #2d3436;
            margin: 0 0 12px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .suggestion-list {
            margin: 0;
            padding-left: 20px;
            color: #636e72;
        }

        .suggestion-list li {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.4;
        }

        .error-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .retry-btn {
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .retry-btn:hover {
            background: linear-gradient(135deg, #00a085 0%, #00b7b8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
        }

        .secondary-btn {
            background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(116, 185, 255, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .secondary-btn:hover {
            background: linear-gradient(135deg, #5498ff 0%, #2d3436 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(116, 185, 255, 0.4);
        }

        .btn-icon {
            font-size: 14px;
            animation: rotate 2s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .retry-btn:hover .btn-icon {
            animation-duration: 0.5s;
        }

        /* Responsive design for error card */
        @media (max-width: 768px) {
            .error-card-content {
                padding: 30px 20px;
            }
            
            .error-title {
                font-size: 20px;
            }
            
            .error-message {
                font-size: 14px;
            }
            
            .error-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .retry-btn, .secondary-btn {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Grand Restaurant - Admin Dashboard</h1>
        <button class="logout-btn" onclick="logout()">Logout</button>
    </div>

    <div class="container">
        <div class="nav-buttons">
            <button class="nav-btn active" onclick="showSection('overview')">Overview</button>
            <button class="nav-btn" onclick="showSection('orders')">Orders</button>
            <button class="nav-btn" onclick="showSection('reservations')">Reservations</button>
            <button class="nav-btn" onclick="showSection('reviews')">Reviews</button>
            <button class="nav-btn" onclick="showSection('menu')">Menu</button>
        </div>

        <!-- Overview Section -->
        <div id="overview" class="section active">
            <h3 class="section-title">Dashboard Overview</h3>
            <p class="section-subtitle">Key metrics and performance indicators</p>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">📊</div>
                    <div class="stat-value" id="todayOrders">-</div>
                    <div class="stat-label">Orders Today</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⏳</div>
                    <div class="stat-value" id="pendingOrders">-</div>
                    <div class="stat-label">Pending Orders</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">💰</div>
                    <div class="stat-value" id="todayRevenue">-</div>
                    <div class="stat-label">Today's Revenue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-value" id="avgRating">-</div>
                    <div class="stat-label">Average Rating</div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div id="orders" class="section">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3>Recent Orders</h3>
                    <button id="clearOrdersBtn" class="action-btn cancel" onclick="clearProcessedOrders()" style="font-size: 12px; padding: 6px 12px;">
                        🗑️ Clear Processed
                    </button>
                </div>
                <div class="loading" id="ordersLoading">Loading orders...</div>
                <div id="ordersTable"></div>
            </div>
        </div>

        <!-- Reservations Section -->
        <div id="reservations" class="section">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3>Recent Reservations</h3>
                    <button id="clearReservationsBtn" class="action-btn cancel" onclick="clearProcessedReservations()" style="font-size: 12px; padding: 6px 12px;">
                        🗑️ Clear Processed
                    </button>
                </div>
                <div class="loading" id="reservationsLoading">Loading reservations...</div>
                <div id="reservationsTable"></div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div id="reviews" class="section">
            <div class="card">
                <h3>Pending Reviews</h3>
                <div class="loading" id="reviewsLoading">Loading reviews...</div>
                <div id="reviewsTable"></div>
            </div>
        </div>

        <!-- Menu Section -->
        <div id="menu" class="section">
            <div class="card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3>Menu Items Management</h3>
                    <div>
                        <select id="categoryFilter" onchange="filterMenuByCategory()" style="padding: 8px; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <option value="">All Categories</option>
                            <option value="Rice & Curry">Rice & Curry</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Seafood">Seafood</option>
                            <option value="Kottu">Kottu</option>
                            <option value="Juices">Juices</option>
                            <option value="Desserts">Desserts</option>
                            <option value="Other Specialties">Other Specialties</option>
                        </select>
                        <button class="action-btn confirm" onclick="openAddItemModal()">+ Add New Item</button>
                    </div>
                </div>
                <div class="menu-stats">
                    <div style="background: #e3f2fd;">
                        <h4 id="totalItems">0</h4>
                        <p>Total Items</p>
                    </div>
                    <div style="background: #e8f5e8;">
                        <h4 id="availableItems">0</h4>
                        <p>Available Items</p>
                    </div>
                    <div style="background: #fff3e0;">
                        <h4 id="totalCategories">0</h4>
                        <p>Categories</p>
                    </div>
                </div>
                <div class="loading" id="menuLoading">Loading menu...</div>
                <div id="menuTable"></div>
            </div>
        </div>

        <!-- Add/Edit Menu Item Modal -->
        <div id="menuModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitle">Add New Menu Item</h3>
                    <span class="close" onclick="closeMenuModal()">&times;</span>
                </div>
                <form id="menuItemForm">
                    <input type="hidden" id="itemId" name="id">
                    
                    <div class="form-group">
                        <label for="itemName">Item Name</label>
                        <input type="text" id="itemName" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="itemDescription">Description</label>
                        <textarea id="itemDescription" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="itemPrice">Price (Rs.)</label>
                            <input type="number" id="itemPrice" name="price" step="0.01" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="itemCategory">Category</label>
                            <select id="itemCategory" name="category" required>
                                <option value="">Select Category</option>
                                <option value="Rice & Curry">Rice & Curry</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Seafood">Seafood</option>
                                <option value="Kottu">Kottu</option>
                                <option value="Juices">Juices</option>
                                <option value="Desserts">Desserts</option>
                                <option value="Other Specialties">Other Specialties</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="itemImage">Image</label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="url" id="itemImage" name="image_url" placeholder="https://example.com/image.jpg" style="flex: 1;">
                            <span style="color: #666;">or</span>
                            <input type="file" id="itemImageFile" accept="image/*" style="flex: 1;" onchange="handleImageUpload(this)">
                        </div>
                        <small style="color: #666; font-size: 12px;">
                            You can either paste an image URL or upload an image file
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="itemAvailable" name="is_available" checked>
                            Available for ordering
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="action-btn cancel" onclick="closeMenuModal()">Cancel</button>
                        <button type="submit" class="action-btn confirm">Save Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '../api';

        // Initialize dashboard when page loads
        window.addEventListener('DOMContentLoaded', function() {
            // Load overview data automatically on page load
            loadOverviewData();
        });

        function showSection(sectionName) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.nav-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected section
            document.getElementById(sectionName).classList.add('active');
            event.target.classList.add('active');
            
            // Load data for the section
            loadSectionData(sectionName);
        }

        function loadSectionData(section) {
            switch(section) {
                case 'overview':
                    loadOverviewData();
                    break;
                case 'orders':
                    loadOrders();
                    break;
                case 'reservations':
                    loadReservations();
                    break;
                case 'reviews':
                    loadReviews();
                    break;
                case 'menu':
                    loadMenu();
                    break;
            }
        }

        async function loadOverviewData() {
            // Set loading state
            document.querySelectorAll('.stat-value').forEach(element => {
                element.innerHTML = '<span class="loading-pulse">•••</span>';
            });
            
            try {
                console.log('Loading overview data...');
                
                // Initialize with default values
                let orderStats = { today: 0, pending: 0, revenue_today: 0 };
                let reviewStats = { average_rating: null };
                
                // Try to load order statistics
                try {
                    const orderStatsResponse = await fetch(`${API_BASE}/orders.php?statistics=1`);
                    if (orderStatsResponse.ok) {
                        orderStats = await orderStatsResponse.json();
                    } else {
                        console.warn('Failed to fetch order stats, using defaults');
                    }
                } catch (err) {
                    console.warn('Order stats API unavailable, using defaults:', err);
                }
                
                // Try to load review statistics
                try {
                    const reviewStatsResponse = await fetch(`${API_BASE}/reviews.php?statistics=1`);
                    if (reviewStatsResponse.ok) {
                        reviewStats = await reviewStatsResponse.json();
                    } else {
                        console.warn('Failed to fetch review stats, using defaults');
                    }
                } catch (err) {
                    console.warn('Review stats API unavailable, using defaults:', err);
                }
                
                // Apply animations when updating values (always use fallback values)
                animateCounter('todayOrders', 0, orderStats.today || 0);
                animateCounter('pendingOrders', 0, orderStats.pending || 0);
                document.getElementById('todayRevenue').textContent = `Rs. ${orderStats.revenue_today || 0}`;
                document.getElementById('avgRating').textContent = reviewStats.average_rating ? `${reviewStats.average_rating}/5` : 'N/A';
                
                // Add a visual indication that data is fresh
                document.querySelectorAll('.stat-card').forEach(card => {
                    card.classList.add('updated');
                    setTimeout(() => {
                        card.classList.remove('updated');
                    }, 1000);
                });
                
                console.log('Overview data loaded successfully');
            } catch (error) {
                console.error('Error loading overview data:', error);
                
                // Show fallback values instead of error messages
                document.getElementById('todayOrders').textContent = '0';
                document.getElementById('pendingOrders').textContent = '0';
                document.getElementById('todayRevenue').textContent = 'Rs. 0';
                document.getElementById('avgRating').textContent = 'N/A';
            }
        }
        
        function animateCounter(elementId, startValue, endValue) {
            const element = document.getElementById(elementId);
            if (!element) return;
            
            // Duration in milliseconds
            const duration = 1000;
            const frameRate = 60;
            const increment = (endValue - startValue) / (duration / (1000 / frameRate));
            
            let currentValue = startValue;
            const counter = setInterval(() => {
                currentValue += increment;
                
                if (
                    (increment > 0 && currentValue >= endValue) || 
                    (increment < 0 && currentValue <= endValue)
                ) {
                    clearInterval(counter);
                    element.textContent = endValue;
                } else {
                    element.textContent = Math.round(currentValue);
                }
            }, 1000 / frameRate);
        }

        async function loadOrders() {
            const container = document.getElementById('ordersTable');
            const loading = document.getElementById('ordersLoading');
            
            try {
                console.log('Loading orders...');
                const response = await fetch(`${API_BASE}/orders.php`);
                console.log('Orders response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const orders = await response.json();
                console.log('Orders received:', orders);
                
                if (!Array.isArray(orders)) {
                    throw new Error('Invalid response format');
                }
                
                if (orders.length === 0) {
                    container.innerHTML = '<p>No orders found.</p>';
                    loading.style.display = 'none';
                    return;
                }

                const table = `
                    <div class="table-wrapper">
                        <table class="admin-table orders-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${orders.map(order => `
                                    <tr>
                                        <td>#${order.id}</td>
                                        <td><strong>${order.customer_name}</strong></td>
                                        <td>${order.order_type}</td>
                                        <td>${order.items || 'N/A'}</td>
                                        <td><strong>Rs. ${order.total_amount}</strong></td>
                                        <td><span class="status ${order.status}">${order.status}</span></td>
                                        <td>${new Date(order.created_at).toLocaleDateString()}</td>
                                        <td>
                                            <div class="action-buttons">
                                                ${order.status === 'pending' ? `<button class="action-btn confirm" onclick="updateOrderStatus(${order.id}, 'preparing')" title="Start Preparing">Start</button>` : ''}
                                                ${order.status === 'preparing' ? `<button class="action-btn confirm" onclick="updateOrderStatus(${order.id}, 'ready')" title="Mark as Ready">Ready</button>` : ''}
                                                ${order.status === 'ready' ? `<button class="action-btn confirm" onclick="updateOrderStatus(${order.id}, 'delivered')" title="Mark as Delivered">Delivered</button>` : ''}
                                                <button class="action-btn cancel" onclick="updateOrderStatus(${order.id}, 'cancelled')" title="Cancel Order">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
                
                container.innerHTML = table;
                loading.style.display = 'none';
                console.log('Orders loaded successfully');
            } catch (error) {
                console.error('Error loading orders:', error);
                container.innerHTML = `
                    <div class="no-data">
                        <div class="no-data-icon">📋</div>
                        <h3>Unable to load orders</h3>
                        <p>There was a problem connecting to the server. Please check your connection and try again.</p>
                        <button class="action-btn" onclick="loadOrders()">Try Again</button>
                    </div>
                `;
                loading.style.display = 'none';
            }
        }

        async function loadReservations() {
            const container = document.getElementById('reservationsTable');
            const loading = document.getElementById('reservationsLoading');
            
            try {
                const reservations = await fetch(`${API_BASE}/reservations.php`).then(r => r.json());
                
                if (reservations.length === 0) {
                    container.innerHTML = '<p>No reservations found.</p>';
                    loading.style.display = 'none';
                    return;
                }

                const table = `
                    <div class="table-wrapper">
                        <table class="admin-table reservations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Guests</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${reservations.map(reservation => `
                                    <tr>
                                        <td>#${reservation.id}</td>
                                        <td><strong>${reservation.name}</strong></td>
                                        <td>${reservation.email}</td>
                                        <td>${reservation.date}</td>
                                        <td>${reservation.time}</td>
                                        <td>${reservation.guests}</td>
                                        <td><span class="status ${reservation.status}">${reservation.status}</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                ${reservation.status === 'pending' ? `<button class="action-btn confirm" onclick="updateReservationStatus(${reservation.id}, 'confirmed')" title="Confirm Reservation">Confirm</button>` : ''}
                                                <button class="action-btn cancel" onclick="updateReservationStatus(${reservation.id}, 'cancelled')" title="Cancel Reservation">Cancel</button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
                
                container.innerHTML = table;
                loading.style.display = 'none';
            } catch (error) {
                console.error('Error loading reservations:', error);
                container.innerHTML = `
                    <div class="no-data">
                        <div class="no-data-icon">📅</div>
                        <h3>Unable to load reservations</h3>
                        <p>There was a problem connecting to the server. Please check your connection and try again.</p>
                        <button class="action-btn" onclick="loadReservations()">Try Again</button>
                    </div>
                `;
                loading.style.display = 'none';
            }
        }

        async function loadReviews() {
            const container = document.getElementById('reviewsTable');
            const loading = document.getElementById('reviewsLoading');
            
            try {
                console.log('Loading reviews...');
                const response = await fetch(`${API_BASE}/reviews.php?admin=1`);
                console.log('Reviews response status:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const reviews = await response.json();
                console.log('Reviews received:', reviews);
                
                if (!Array.isArray(reviews)) {
                    throw new Error('Invalid response format');
                }
                
                if (reviews.length === 0) {
                    container.innerHTML = '<p>No reviews found.</p>';
                    loading.style.display = 'none';
                    return;
                }

                const table = `
                    <div class="table-wrapper">
                        <table class="admin-table reviews-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${reviews.map(review => `
                                    <tr>
                                        <td>#${review.id}</td>
                                        <td><strong>${review.customer_name}</strong></td>
                                        <td>${'★'.repeat(review.rating)}${'☆'.repeat(5-review.rating)}</td>
                                        <td>${review.review_text.substring(0, 150)}${review.review_text.length > 150 ? '...' : ''}</td>
                                        <td><span class="status ${review.is_approved ? 'confirmed' : 'pending'}">${review.is_approved ? 'Approved' : 'Pending'}</span></td>
                                        <td>${new Date(review.created_at).toLocaleDateString()}</td>
                                        <td>
                                            <div class="action-buttons">
                                                ${!review.is_approved ? `<button class="action-btn approve" onclick="updateReviewStatus(${review.id}, 'approve')" title="Approve Review">Approve</button>` : ''}
                                                <button class="action-btn reject" onclick="updateReviewStatus(${review.id}, 'reject')" title="Reject Review">Reject</button>
                                                <button class="action-btn delete" onclick="deleteReview(${review.id})" title="Delete Review">Remove</button>
                                            </div>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
                
                container.innerHTML = table;
                loading.style.display = 'none';
                console.log('Reviews loaded successfully');
            } catch (error) {
                console.error('Error loading reviews:', error);
                container.innerHTML = `
                    <div class="error-card">
                        <div class="error-card-content">
                            <div class="error-icon">
                                <span class="error-icon-circle">⚠️</span>
                            </div>
                            <div class="error-details">
                                <h3 class="error-title">Review Loading Error</h3>
                                <p class="error-message">Unable to retrieve review data from the database. This could be due to a temporary server issue or network connectivity problem.</p>
                                <div class="error-suggestions">
                                    <p class="suggestion-title">Try these solutions:</p>
                                    <ul class="suggestion-list">
                                        <li>Check your internet connection</li>
                                        <li>Refresh the page</li>
                                        <li>Verify the server is running</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="error-actions">
                                <button class="retry-btn" onclick="loadReviews()">
                                    <span class="btn-icon">🔄</span>
                                    Reload Reviews
                                </button>
                                <button class="secondary-btn" onclick="location.reload()">
                                    <span class="btn-icon">↻</span>
                                    Refresh Page
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                loading.style.display = 'none';
            }
        }

        let allMenuItems = []; // Store all menu items for filtering

        async function loadMenu() {
            const container = document.getElementById('menuTable');
            const loading = document.getElementById('menuLoading');
            
            try {
                console.log('Loading menu items...'); // Debug log
                const response = await fetch(`${API_BASE}/menu.php?admin=true`);
                console.log('Response status:', response.status); // Debug log
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Get the response text first to check for errors
                const responseText = await response.text();
                console.log('Raw response:', responseText.substring(0, 200)); // Debug log (first 200 chars)
                
                // Try to parse as JSON
                let menuItems;
                try {
                    menuItems = JSON.parse(responseText);
                } catch (jsonError) {
                    console.error('JSON parsing error:', jsonError);
                    console.error('Response text:', responseText);
                    throw new Error(`Invalid JSON response: ${jsonError.message}. Response: ${responseText.substring(0, 100)}...`);
                }
                
                console.log('Menu items received:', menuItems); // Debug log
                
                if (!Array.isArray(menuItems)) {
                    throw new Error('Invalid response format - expected array');
                }

                // Store all items for filtering
                allMenuItems = menuItems;
                
                // Update statistics
                updateMenuStatistics(menuItems);
                
                if (menuItems.length === 0) {
                    container.innerHTML = '<div style="text-align: center; padding: 40px;"><p>No menu items found.</p><p>Start by adding your first menu item!</p></div>';
                    loading.style.display = 'none';
                    return;
                }

                displayMenuItems(menuItems);
                loading.style.display = 'none';
                console.log('Menu loaded successfully'); // Debug log
            } catch (error) {
                console.error('Error loading menu:', error); // Debug log
                container.innerHTML = `
                    <div class="no-data">
                        <div class="no-data-icon">🍽️</div>
                        <h3>Unable to load menu</h3>
                        <p>There was a problem connecting to the server. Please check your connection and try again.</p>
                        <button class="action-btn" onclick="loadMenu()">Try Again</button>
                    </div>
                `;
                loading.style.display = 'none';
            }
        }

        function updateMenuStatistics(menuItems) {
            const totalItems = menuItems.length;
            const availableItems = menuItems.filter(item => item.is_available == 1).length;
            const categories = [...new Set(menuItems.map(item => item.category))].length;

            document.getElementById('totalItems').textContent = totalItems;
            document.getElementById('availableItems').textContent = availableItems;
            document.getElementById('totalCategories').textContent = categories;
        }

        function displayMenuItems(menuItems) {
            const container = document.getElementById('menuTable');
            
            if (menuItems.length === 0) {
                container.innerHTML = '<div style="text-align: center; padding: 20px;"><p>No items found for the selected filter.</p></div>';
                return;
            }

            const table = `
                <div class="table-wrapper">
                    <table class="admin-table menu-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Available</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${menuItems.map(item => `
                                <tr>
                                    <td>#${item.id}</td>
                                    <td><strong>${item.name}</strong></td>
                                    <td><span class="category-badge" style="background-color: ${getCategoryColor(item.category)}; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8em;">${item.category}</span></td>
                                    <td><strong>Rs. ${item.price}</strong></td>
                                    <td><span class="status ${item.is_available ? 'confirmed' : 'cancelled'}">${item.is_available ? 'Yes' : 'No'}</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn confirm" onclick="editMenuItem(${item.id})" title="Edit Item">Edit</button>
                                            <button class="action-btn ${item.is_available ? 'cancel' : 'confirm'}" onclick="toggleMenuItem(${item.id}, ${!item.is_available})" title="${item.is_available ? 'Disable' : 'Enable'} Item">
                                                ${item.is_available ? 'Disable' : 'Enable'}
                                            </button>
                                            <button class="action-btn reject" onclick="hardDeleteMenuItem(${item.id})" title="Permanently delete from database">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = table;
        }

        function getCategoryColor(category) {
            const colors = {
                'Rice & Curry': '#228B22',
                'Chinese': '#DC143C',
                'Seafood': '#4682B4',
                'Kottu': '#FF6347',
                'Juices': '#32CD32',
                'Desserts': '#DA70D6',
                'Other Specialties': '#9370DB'
            };
            return colors[category] || '#6c757d';
        }

        function filterMenuByCategory() {
            const selectedCategory = document.getElementById('categoryFilter').value;
            
            if (!selectedCategory) {
                displayMenuItems(allMenuItems);
            } else {
                const filteredItems = allMenuItems.filter(item => item.category === selectedCategory);
                displayMenuItems(filteredItems);
            }
        }

        async function updateOrderStatus(orderId, status) {
            try {
                const response = await fetch(`${API_BASE}/orders.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: orderId, status: status })
                });

                if (response.ok) {
                    loadOrders(); // Reload orders
                    loadOverviewData(); // Update overview stats
                } else {
                    alert('Failed to update order status');
                }
            } catch (error) {
                alert('Error updating order status');
            }
        }

        async function updateReservationStatus(reservationId, status) {
            try {
                const response = await fetch(`${API_BASE}/reservations.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: reservationId, status: status })
                });

                if (response.ok) {
                    loadReservations(); // Reload reservations
                } else {
                    alert('Failed to update reservation status');
                }
            } catch (error) {
                alert('Error updating reservation status');
            }
        }

        async function clearProcessedReservations() {
            // Confirmation dialog
            const confirmed = confirm(
                'Are you sure you want to clear all cancelled and confirmed reservations?\n\n' +
                'This action cannot be undone and will permanently delete processed reservations from the database.'
            );
            
            if (!confirmed) {
                return;
            }

            const clearBtn = document.getElementById('clearReservationsBtn');
            const originalText = clearBtn.textContent;
            
            try {
                // Disable button and show loading state
                clearBtn.disabled = true;
                clearBtn.textContent = '🔄 Clearing...';

                const response = await fetch(`${API_BASE}/reservations.php?clear_processed=true`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    alert(`✅ ${result.message}`);
                    loadReservations(); // Reload reservations to show updated list
                    loadOverviewData(); // Update overview stats
                } else {
                    alert(`❌ Error: ${result.error || 'Failed to clear reservations'}`);
                }
            } catch (error) {
                console.error('Error clearing reservations:', error);
                alert('❌ Network error occurred while clearing reservations');
            } finally {
                // Re-enable button
                clearBtn.disabled = false;
                clearBtn.textContent = originalText;
            }
        }

        async function clearProcessedOrders() {
            // Confirmation dialog
            const confirmed = confirm(
                'Are you sure you want to clear all delivered and cancelled orders?\n\n' +
                'This action cannot be undone and will permanently delete processed orders from the database.'
            );
            
            if (!confirmed) {
                return;
            }

            const clearBtn = document.getElementById('clearOrdersBtn');
            const originalText = clearBtn.textContent;
            
            try {
                // Disable button and show loading state
                clearBtn.disabled = true;
                clearBtn.textContent = '🔄 Clearing...';

                const response = await fetch(`${API_BASE}/orders.php?clear_processed=true`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok) {
                    alert(`✅ ${result.message}`);
                    loadOrders(); // Reload orders to show updated list
                    loadOverviewData(); // Update overview stats
                } else {
                    alert(`❌ Error: ${result.error || 'Failed to clear orders'}`);
                }
            } catch (error) {
                console.error('Error clearing orders:', error);
                alert('❌ Network error occurred while clearing orders');
            } finally {
                // Re-enable button
                clearBtn.disabled = false;
                clearBtn.textContent = originalText;
            }
        }

        async function updateReviewStatus(reviewId, action) {
            try {
                const response = await fetch(`${API_BASE}/reviews.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: reviewId, action: action })
                });

                if (response.ok) {
                    loadReviews(); // Reload reviews
                    loadOverviewData(); // Update overview stats
                } else {
                    alert('Failed to update review status');
                }
            } catch (error) {
                alert('Error updating review status');
            }
        }

        async function deleteReview(reviewId) {
            // Show confirmation dialog
            const confirmed = confirm(
                'Are you sure you want to permanently delete this review?\n\n' +
                'This action cannot be undone and will remove the review from the database.'
            );
            
            if (!confirmed) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE}/reviews.php?id=${reviewId}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    alert('✅ Review deleted successfully');
                    loadReviews(); // Reload reviews list
                    loadOverviewData(); // Update overview statistics
                } else {
                    const error = await response.json();
                    alert('❌ Failed to delete review: ' + (error.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting review:', error);
                alert('❌ Network error occurred while deleting review');
            }
        }

        async function toggleMenuItem(itemId, isAvailable) {
            try {
                const response = await fetch(`${API_BASE}/menu.php`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        id: itemId, 
                        is_available: isAvailable ? 1 : 0 
                    })
                });

                if (response.ok) {
                    loadMenu(); // Reload menu
                    alert(`Menu item ${isAvailable ? 'enabled' : 'disabled'} successfully`);
                } else {
                    alert('Failed to update menu item');
                }
            } catch (error) {
                alert('Error updating menu item');
            }
        }

        // Menu Modal Functions
        function openAddItemModal() {
            document.getElementById('modalTitle').textContent = 'Add New Menu Item';
            document.getElementById('menuItemForm').reset();
            document.getElementById('itemId').value = '';
            document.getElementById('itemAvailable').checked = true;
            document.getElementById('menuModal').style.display = 'flex';
        }

        async function editMenuItem(itemId) {
            try {
                const response = await fetch(`${API_BASE}/menu.php?id=${itemId}&admin=true`);
                const item = await response.json();
                
                if (item) {
                    document.getElementById('modalTitle').textContent = 'Edit Menu Item';
                    document.getElementById('itemId').value = item.id;
                    document.getElementById('itemName').value = item.name;
                    document.getElementById('itemDescription').value = item.description || '';
                    document.getElementById('itemPrice').value = item.price;
                    document.getElementById('itemCategory').value = item.category;
                    document.getElementById('itemImage').value = item.image_url || '';
                    document.getElementById('itemAvailable').checked = item.is_available == 1;
                    document.getElementById('menuModal').style.display = 'flex';
                } else {
                    alert('Menu item not found');
                }
            } catch (error) {
                alert('Error loading menu item');
            }
        }

        function closeMenuModal() {
            document.getElementById('menuModal').style.display = 'none';
        }

        // Image Upload Handler
        async function handleImageUpload(input) {
            const file = input.files[0];
            if (!file) return;
            
            // Show loading state
            const originalText = document.getElementById('itemImage').value;
            document.getElementById('itemImage').value = 'Uploading...';
            document.getElementById('itemImage').disabled = true;
            
            try {
                // Create FormData for file upload
                const formData = new FormData();
                formData.append('image', file);
                
                // Upload the file
                const response = await fetch(`${API_BASE}/upload_image.php`, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    // Set the uploaded image URL
                    document.getElementById('itemImage').value = result.url;
                    document.getElementById('itemImage').disabled = false;
                    
                    // Show preview
                    showImagePreview(result.url);
                    
                    alert('Image uploaded successfully!');
                } else {
                    throw new Error(result.error || 'Upload failed');
                }
            } catch (error) {
                console.error('Upload error:', error);
                document.getElementById('itemImage').value = originalText;
                document.getElementById('itemImage').disabled = false;
                alert('Failed to upload image: ' + error.message);
            }
        }
        
        function showImagePreview(imageUrl) {
            // Remove existing preview
            const existingPreview = document.getElementById('imagePreview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Create new preview
            const preview = document.createElement('div');
            preview.id = 'imagePreview';
            preview.style.marginTop = '10px';
            preview.innerHTML = `
                <img src="${imageUrl}" alt="Preview" style="max-width: 200px; max-height: 150px; border-radius: 5px; border: 1px solid #ddd;">
                <div style="font-size: 12px; color: #666; margin-top: 5px;">Image Preview</div>
            `;
            
            // Insert after the image input group
            const imageGroup = document.getElementById('itemImage').closest('.form-group');
            imageGroup.appendChild(preview);
        }

        // Hard delete - permanently remove from database
        async function hardDeleteMenuItem(itemId) {
            if (confirm('⚠️ PERMANENTLY DELETE this menu item from the database?\n\nThis action CANNOT be undone!\n\nThe item will be completely removed and cannot be recovered.')) {
                try {
                    const response = await fetch(`${API_BASE}/menu.php?id=${itemId}&hard=true`, {
                        method: 'DELETE'
                    });

                    if (response.ok) {
                        loadMenu(); // Reload menu
                        alert('Menu item permanently deleted from database');
                    } else {
                        const errorData = await response.json();
                        alert('Failed to delete menu item: ' + (errorData.error || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                    alert('Error deleting menu item: ' + error.message);
                }
            }
        }

        // Legacy function - now redirects to hard delete for backwards compatibility
        async function deleteMenuItem(itemId) {
            hardDeleteMenuItem(itemId);
        }

        // Handle menu item form submission
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('menuItemForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const itemData = {
                    name: formData.get('name'),
                    description: formData.get('description'),
                    price: parseFloat(formData.get('price')),
                    category: formData.get('category'),
                    image_url: formData.get('image_url'),
                    is_available: formData.get('is_available') ? 1 : 0
                };

                const itemId = formData.get('id');
                const isEdit = itemId && itemId !== '';

                try {
                    const response = await fetch(`${API_BASE}/menu.php`, {
                        method: isEdit ? 'PUT' : 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(isEdit ? { ...itemData, id: itemId } : itemData)
                    });

                    if (response.ok) {
                        closeMenuModal();
                        loadMenu(); // Reload menu
                        alert(`Menu item ${isEdit ? 'updated' : 'added'} successfully`);
                    } else {
                        const error = await response.json();
                        alert(error.error || 'Failed to save menu item');
                    }
                } catch (error) {
                    alert('Error saving menu item');
                }
            });

            // Close modal when clicking outside of it
            document.getElementById('menuModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeMenuModal();
                }
            });
        });

        // Authentication Functions
        function logout() {
            localStorage.removeItem('admin_logged_in');
            localStorage.removeItem('admin_username');
            window.location.href = 'login.php';
        }

        // Check if user is logged in
        function checkAuth() {
            if (localStorage.getItem('admin_logged_in') !== 'true') {
                window.location.href = 'login.php';
            }
        }

        // Function to handle initialization
        function initializeDashboard() {
            // Check if user is authenticated
            checkAuth(); 
            
            // Initialize overview statistics
            loadOverviewData();
            
            console.log('Dashboard initialized successfully');
        }
        
        // Load initial data
        window.addEventListener('load', initializeDashboard);
    </script>
</body>
</html>