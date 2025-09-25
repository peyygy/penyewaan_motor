<!DOCTYPE html><!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head><head>

    <meta charset="utf-8">    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>{{ config('app.name', 'LUXMOTO Premium') }} - @yield('title', 'Dashboard')</title>    <title>{{ config('app.name', 'LUXMOTO Premium') }} - @yield('title', 'Dashboard')</title>



    <!-- Bootstrap CSS -->    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->    <!-- Bootstrap Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Google Fonts - Premium Typography -->    <!-- Google Fonts - Premium Typography -->

    <link rel="preconnect" href="https://fonts.googleapis.com">    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">

        

    <!-- LUXURY DESIGN SYSTEM -->    <!-- LUXURY DESIGN SYSTEM -->

    <style>    <style>

        :root {        :root {

            /* === LUXURY COLOR PALETTE === */            /* === LUXURY COLOR PALETTE === */

            /* Primary Dark Monochromatic */            /* Primary Dark Monochromatic */

            --luxmoto-primary: #0A0A0B;          /* Deep Charcoal */            --luxmoto-primary: #0A0A0B;          /* Deep Charcoal */

            --luxmoto-secondary: #1A1A1D;        /* Rich Black */            --luxmoto-secondary: #1A1A1D;        /* Rich Black */

            --luxmoto-tertiary: #2D2D33;         /* Elegant Gray */            --luxmoto-tertiary: #2D2D33;         /* Elegant Gray */

            --luxmoto-surface: #3A3A42;          /* Surface Gray */            --luxmoto-surface: #3A3A42;          /* Surface Gray */

                        

            /* Gold Luxury Accents */            /* Gold Luxury Accents */

            --luxmoto-gold-primary: #D4AF37;     /* Premium Gold */            --luxmoto-gold-primary: #D4AF37;     /* Premium Gold */

            --luxmoto-gold-light: #F4E4BC;       /* Champagne Gold */            --luxmoto-gold-light: #F4E4BC;       /* Champagne Gold */

            --luxmoto-gold-dark: #B8941F;        /* Deep Gold */            --luxmoto-gold-dark: #B8941F;        /* Deep Gold */

                        

            /* Neutral Sophistication */            /* Neutral Sophistication */

            --luxmoto-white: #FAFAFA;            /* Pure White */            --luxmoto-white: #FAFAFA;            /* Pure White */

            --luxmoto-light-gray: #E8E8EA;       /* Light Gray */            --luxmoto-light-gray: #E8E8EA;       /* Light Gray */

            --luxmoto-medium-gray: #9CA3AF;      /* Medium Gray */            --luxmoto-medium-gray: #9CA3AF;      /* Medium Gray */

            --luxmoto-dark-gray: #6B7280;        /* Dark Gray */            --luxmoto-dark-gray: #6B7280;        /* Dark Gray */

                        

            /* Status Colors - Premium */            /* Status Colors - Premium */

            --luxmoto-success: #059669;          /* Emerald */            --luxmoto-success: #059669;          /* Emerald */

            --luxmoto-warning: #D97706;          /* Amber */            --luxmoto-warning: #D97706;          /* Amber */

            --luxmoto-danger: #DC2626;           /* Red */            --luxmoto-danger: #DC2626;           /* Red */

            --luxmoto-info: #2563EB;             /* Blue */            --luxmoto-info: #2563EB;             /* Blue */

                        

            /* Typography */            /* Typography */

            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;            --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

            --font-display: 'Playfair Display', Georgia, serif;            --font-display: 'Playfair Display', Georgia, serif;

                        

            /* Shadows & Effects */            /* Shadows & Effects */

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);

            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);

            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);

            --shadow-luxury: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);            --shadow-luxury: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

                        

            /* Gradients */            /* Gradients */

            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #F4E4BC 100%);            --gradient-gold: linear-gradient(135deg, #D4AF37 0%, #F4E4BC 100%);

            --gradient-dark: linear-gradient(135deg, #0A0A0B 0%, #1A1A1D 100%);            --gradient-dark: linear-gradient(135deg, #0A0A0B 0%, #1A1A1D 100%);

            --gradient-surface: linear-gradient(135deg, #2D2D33 0%, #3A3A42 100%);            --gradient-surface: linear-gradient(135deg, #2D2D33 0%, #3A3A42 100%);

        }        }



        * {        * {

            box-sizing: border-box;            box-sizing: border-box;

        }        }



        body {        body {

            font-family: var(--font-primary);            font-family: var(--font-primary);

            font-weight: 400;            font-weight: 400;

            line-height: 1.6;            line-height: 1.6;

            color: var(--luxmoto-dark-gray);            color: var(--luxmoto-dark-gray);

            background: var(--luxmoto-white);            background: var(--luxmoto-white);

            -webkit-font-smoothing: antialiased;            -webkit-font-smoothing: antialiased;

            -moz-osx-font-smoothing: grayscale;            -moz-osx-font-smoothing: grayscale;

        }        }



        /* === LUXURY SIDEBAR === */        /* === LUXURY SIDEBAR === */

        .sidebar {        .sidebar {

            min-height: 100vh;            min-height: 100vh;

            background: var(--gradient-dark);            background: var(--gradient-dark);

            border-right: 1px solid rgba(212, 175, 55, 0.1);            border-right: 1px solid rgba(212, 175, 55, 0.1);

            position: relative;            position: relative;

            overflow: hidden;            overflow: hidden;

        }        }



        .sidebar::before {        .sidebar::before {

            content: '';            content: '';

            position: absolute;            position: absolute;

            top: 0;            top: 0;

            right: 0;            right: 0;

            width: 2px;            width: 2px;

            height: 100%;            height: 100%;

            background: var(--gradient-gold);            background: var(--gradient-gold);

            opacity: 0.7;            opacity: 0.7;

        }        }



        .sidebar .brand-section {        .sidebar .brand-section {

            padding: 2rem 1.5rem;            padding: 2rem 1.5rem;

            border-bottom: 1px solid rgba(212, 175, 55, 0.1);            border-bottom: 1px solid rgba(212, 175, 55, 0.1);

            text-align: center;            text-align: center;

        }        }



        .sidebar .brand-title {        .sidebar .brand-title {

            font-family: var(--font-display);            font-family: var(--font-display);

            font-weight: 600;            font-weight: 600;

            font-size: 1.5rem;            font-size: 1.5rem;

            color: var(--luxmoto-gold-primary);            color: var(--luxmoto-gold-primary);

            margin: 0;            margin: 0;

            letter-spacing: -0.025em;            letter-spacing: -0.025em;

        }        }



        .sidebar .brand-subtitle {        .sidebar .brand-subtitle {

            font-size: 0.75rem;            font-size: 0.75rem;

            color: var(--luxmoto-medium-gray);            color: var(--luxmoto-medium-gray);

            margin-top: 0.25rem;            margin-top: 0.25rem;

            text-transform: uppercase;            text-transform: uppercase;

            letter-spacing: 0.1em;            letter-spacing: 0.1em;

        }        }



        .sidebar .nav-link {        .sidebar .nav-link {

            color: var(--luxmoto-light-gray);            color: var(--luxmoto-light-gray);

            padding: 1rem 1.5rem;            padding: 1rem 1.5rem;

            margin: 0.25rem 1rem;            margin: 0.25rem 1rem;

            border-radius: 0.75rem;            border-radius: 0.75rem;

            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            font-weight: 500;            font-weight: 500;

            position: relative;            position: relative;

            display: flex;            display: flex;

            align-items: center;            align-items: center;

            text-decoration: none;            text-decoration: none;

        }        }



        .sidebar .nav-link i {        .sidebar .nav-link i {

            margin-right: 0.75rem;            margin-right: 0.75rem;

            font-size: 1.125rem;            font-size: 1.125rem;

            opacity: 0.8;            opacity: 0.8;

        }        }



        .sidebar .nav-link:hover {        .sidebar .nav-link:hover {

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            background: rgba(212, 175, 55, 0.1);            background: rgba(212, 175, 55, 0.1);

            transform: translateX(4px);            transform: translateX(4px);

        }        }



        .sidebar .nav-link.active {        .sidebar .nav-link.active {

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            background: var(--luxmoto-gold-primary);            background: var(--luxmoto-gold-primary);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .sidebar .nav-link.active i {        .sidebar .nav-link.active i {

            opacity: 1;            opacity: 1;

        }        }



        /* === LUXURY CONTENT AREA === */        /* === LUXURY CONTENT AREA === */

        .content {        .content {

            background: linear-gradient(135deg, #FAFAFA 0%, #F3F4F6 100%);            background: linear-gradient(135deg, #FAFAFA 0%, #F3F4F6 100%);

            min-height: 100vh;            min-height: 100vh;

            position: relative;            position: relative;

        }        }



        /* === PREMIUM NAVBAR === */        /* === PREMIUM NAVBAR === */

        .navbar {        .navbar {

            background: rgba(255, 255, 255, 0.95);            background: rgba(255, 255, 255, 0.95);

            backdrop-filter: blur(20px);            backdrop-filter: blur(20px);

            border-bottom: 1px solid rgba(212, 175, 55, 0.1);            border-bottom: 1px solid rgba(212, 175, 55, 0.1);

            box-shadow: var(--shadow-sm);            box-shadow: var(--shadow-sm);

            padding: 1rem 2rem;            padding: 1rem 2rem;

        }        }



        .navbar .navbar-brand {        .navbar .navbar-brand {

            font-family: var(--font-display);            font-family: var(--font-display);

            font-weight: 600;            font-weight: 600;

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

        }        }



        /* === LUXURY CARDS === */        /* === LUXURY CARDS === */

        .card {        .card {

            border: none;            border: none;

            border-radius: 1rem;            border-radius: 1rem;

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

            background: rgba(255, 255, 255, 0.95);            background: rgba(255, 255, 255, 0.95);

            backdrop-filter: blur(10px);            backdrop-filter: blur(10px);

            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            overflow: hidden;            overflow: hidden;

            position: relative;            position: relative;

        }        }



        .card::before {        .card::before {

            content: '';            content: '';

            position: absolute;            position: absolute;

            top: 0;            top: 0;

            left: 0;            left: 0;

            right: 0;            right: 0;

            height: 4px;            height: 4px;

            background: var(--gradient-gold);            background: var(--gradient-gold);

            opacity: 0;            opacity: 0;

            transition: opacity 0.3s ease;            transition: opacity 0.3s ease;

        }        }



        .card:hover {        .card:hover {

            transform: translateY(-2px);            transform: translateY(-2px);

            box-shadow: var(--shadow-luxury);            box-shadow: var(--shadow-luxury);

        }        }



        .card:hover::before {        .card:hover::before {

            opacity: 1;            opacity: 1;

        }        }



        .card-header {        .card-header {

            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0.02) 100%);            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0.02) 100%);

            border-bottom: 1px solid rgba(212, 175, 55, 0.1);            border-bottom: 1px solid rgba(212, 175, 55, 0.1);

            padding: 1.5rem;            padding: 1.5rem;

        }        }



        .card-title {        .card-title {

            font-family: var(--font-display);            font-family: var(--font-display);

            font-weight: 600;            font-weight: 600;

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            margin: 0;            margin: 0;

            font-size: 1.125rem;            font-size: 1.125rem;

        }        }



        .card-body {        .card-body {

            padding: 1.5rem;            padding: 1.5rem;

        }        }



        /* === PREMIUM STATISTICS CARDS === */        /* === PREMIUM STATISTICS CARDS === */

        .stat-card {        .stat-card {

            border-left: 4px solid var(--luxmoto-gold-primary);            border-left: 4px solid var(--luxmoto-gold-primary);

            background: var(--gradient-surface);            background: var(--gradient-surface);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            position: relative;            position: relative;

            overflow: hidden;            overflow: hidden;

        }        }



        .stat-card::after {        .stat-card::after {

            content: '';            content: '';

            position: absolute;            position: absolute;

            top: -50%;            top: -50%;

            right: -50%;            right: -50%;

            width: 100%;            width: 100%;

            height: 200%;            height: 200%;

            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);

            pointer-events: none;            pointer-events: none;

        }        }



        .stat-card.success {        .stat-card.success {

            border-left-color: var(--luxmoto-success);            border-left-color: var(--luxmoto-success);

            background: linear-gradient(135deg, #064E3B 0%, #065F46 100%);            background: linear-gradient(135deg, #064E3B 0%, #065F46 100%);

        }        }



        .stat-card.warning {        .stat-card.warning {

            border-left-color: var(--luxmoto-warning);            border-left-color: var(--luxmoto-warning);

            background: linear-gradient(135deg, #92400E 0%, #B45309 100%);            background: linear-gradient(135deg, #92400E 0%, #B45309 100%);

        }        }



        .stat-card.danger {        .stat-card.danger {

            border-left-color: var(--luxmoto-danger);            border-left-color: var(--luxmoto-danger);

            background: linear-gradient(135deg, #991B1B 0%, #B91C1C 100%);            background: linear-gradient(135deg, #991B1B 0%, #B91C1C 100%);

        }        }



        .stat-card.info {        .stat-card.info {

            border-left-color: var(--luxmoto-info);            border-left-color: var(--luxmoto-info);

            background: linear-gradient(135deg, #1E3A8A 0%, #1D4ED8 100%);            background: linear-gradient(135deg, #1E3A8A 0%, #1D4ED8 100%);

        }        }



        .stat-number {        .stat-number {

            font-family: var(--font-display);            font-family: var(--font-display);

            font-size: 2.5rem;            font-size: 2.5rem;

            font-weight: 700;            font-weight: 700;

            line-height: 1;            line-height: 1;

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

        }        }



        .stat-label {        .stat-label {

            font-size: 0.875rem;            font-size: 0.875rem;

            color: rgba(255, 255, 255, 0.8);            color: rgba(255, 255, 255, 0.8);

            text-transform: uppercase;            text-transform: uppercase;

            letter-spacing: 0.05em;            letter-spacing: 0.05em;

            margin-top: 0.5rem;            margin-top: 0.5rem;

        }        }



        /* === LUXURY BUTTONS === */        /* === LUXURY BUTTONS === */

        .btn {        .btn {

            font-weight: 500;            font-weight: 500;

            border-radius: 0.75rem;            border-radius: 0.75rem;

            padding: 0.75rem 1.5rem;            padding: 0.75rem 1.5rem;

            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            border: none;            border: none;

            position: relative;            position: relative;

            overflow: hidden;            overflow: hidden;

            text-transform: none;            text-transform: none;

            letter-spacing: 0.025em;            letter-spacing: 0.025em;

        }        }



        .btn::before {        .btn::before {

            content: '';            content: '';

            position: absolute;            position: absolute;

            top: 0;            top: 0;

            left: -100%;            left: -100%;

            width: 100%;            width: 100%;

            height: 100%;            height: 100%;

            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);

            transition: left 0.6s;            transition: left 0.6s;

        }        }



        .btn:hover::before {        .btn:hover::before {

            left: 100%;            left: 100%;

        }        }



        .btn-primary {        .btn-primary {

            background: var(--gradient-gold);            background: var(--gradient-gold);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .btn-primary:hover {        .btn-primary:hover {

            background: var(--luxmoto-gold-dark);            background: var(--luxmoto-gold-dark);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            transform: translateY(-2px);            transform: translateY(-2px);

            box-shadow: var(--shadow-lg);            box-shadow: var(--shadow-lg);

        }        }



        .btn-secondary {        .btn-secondary {

            background: var(--gradient-surface);            background: var(--gradient-surface);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .btn-secondary:hover {        .btn-secondary:hover {

            background: var(--luxmoto-surface);            background: var(--luxmoto-surface);

            transform: translateY(-2px);            transform: translateY(-2px);

            box-shadow: var(--shadow-lg);            box-shadow: var(--shadow-lg);

        }        }



        .btn-success {        .btn-success {

            background: linear-gradient(135deg, var(--luxmoto-success) 0%, #10B981 100%);            background: linear-gradient(135deg, var(--luxmoto-success) 0%, #10B981 100%);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .btn-warning {        .btn-warning {

            background: linear-gradient(135deg, var(--luxmoto-warning) 0%, #F59E0B 100%);            background: linear-gradient(135deg, var(--luxmoto-warning) 0%, #F59E0B 100%);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .btn-danger {        .btn-danger {

            background: linear-gradient(135deg, var(--luxmoto-danger) 0%, #EF4444 100%);            background: linear-gradient(135deg, var(--luxmoto-danger) 0%, #EF4444 100%);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .btn-info {        .btn-info {

            background: linear-gradient(135deg, var(--luxmoto-info) 0%, #3B82F6 100%);            background: linear-gradient(135deg, var(--luxmoto-info) 0%, #3B82F6 100%);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .btn-outline-primary {        .btn-outline-primary {

            border: 2px solid var(--luxmoto-gold-primary);            border: 2px solid var(--luxmoto-gold-primary);

            color: var(--luxmoto-gold-primary);            color: var(--luxmoto-gold-primary);

            background: transparent;            background: transparent;

        }        }



        .btn-outline-primary:hover {        .btn-outline-primary:hover {

            background: var(--luxmoto-gold-primary);            background: var(--luxmoto-gold-primary);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            transform: translateY(-2px);            transform: translateY(-2px);

            box-shadow: var(--shadow-lg);            box-shadow: var(--shadow-lg);

        }        }



        .btn-sm {        .btn-sm {

            padding: 0.5rem 1rem;            padding: 0.5rem 1rem;

            font-size: 0.875rem;            font-size: 0.875rem;

        }        }



        .btn-lg {        .btn-lg {

            padding: 1rem 2rem;            padding: 1rem 2rem;

            font-size: 1.125rem;            font-size: 1.125rem;

        }        }



        /* === LUXURY FORMS === */        /* === LUXURY FORMS === */

        .form-control, .form-select {        .form-control, .form-select {

            border: 2px solid var(--luxmoto-light-gray);            border: 2px solid var(--luxmoto-light-gray);

            border-radius: 0.75rem;            border-radius: 0.75rem;

            padding: 0.75rem 1rem;            padding: 0.75rem 1rem;

            font-weight: 400;            font-weight: 400;

            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

            background: rgba(255, 255, 255, 0.9);            background: rgba(255, 255, 255, 0.9);

            backdrop-filter: blur(10px);            backdrop-filter: blur(10px);

        }        }



        .form-control:focus, .form-select:focus {        .form-control:focus, .form-select:focus {

            border-color: var(--luxmoto-gold-primary);            border-color: var(--luxmoto-gold-primary);

            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);

            background: var(--luxmoto-white);            background: var(--luxmoto-white);

        }        }



        .form-label {        .form-label {

            font-weight: 500;            font-weight: 500;

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            margin-bottom: 0.5rem;            margin-bottom: 0.5rem;

            font-size: 0.9rem;            font-size: 0.9rem;

        }        }



        .input-group-text {        .input-group-text {

            background: var(--luxmoto-gold-light);            background: var(--luxmoto-gold-light);

            border: 2px solid var(--luxmoto-gold-primary);            border: 2px solid var(--luxmoto-gold-primary);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            font-weight: 500;            font-weight: 500;

            border-radius: 0.75rem 0 0 0.75rem;            border-radius: 0.75rem 0 0 0.75rem;

        }        }



        .form-control:invalid {        .form-control:invalid {

            border-color: var(--luxmoto-danger);            border-color: var(--luxmoto-danger);

        }        }



        .invalid-feedback {        .invalid-feedback {

            color: var(--luxmoto-danger);            color: var(--luxmoto-danger);

            font-weight: 500;            font-weight: 500;

        }        }



        /* === LUXURY TABLES === */        /* === LUXURY TABLES === */

        .table {        .table {

            background: rgba(255, 255, 255, 0.9);            background: rgba(255, 255, 255, 0.9);

            backdrop-filter: blur(10px);            backdrop-filter: blur(10px);

            border-radius: 1rem;            border-radius: 1rem;

            overflow: hidden;            overflow: hidden;

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .table thead th {        .table thead th {

            background: var(--gradient-surface);            background: var(--gradient-surface);

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

            border: none;            border: none;

            font-weight: 600;            font-weight: 600;

            text-transform: uppercase;            text-transform: uppercase;

            letter-spacing: 0.05em;            letter-spacing: 0.05em;

            font-size: 0.875rem;            font-size: 0.875rem;

            padding: 1.25rem 1rem;            padding: 1.25rem 1rem;

        }        }



        .table tbody td {        .table tbody td {

            border-color: rgba(212, 175, 55, 0.1);            border-color: rgba(212, 175, 55, 0.1);

            padding: 1rem;            padding: 1rem;

            vertical-align: middle;            vertical-align: middle;

        }        }



        .table tbody tr {        .table tbody tr {

            transition: all 0.3s ease;            transition: all 0.3s ease;

        }        }



        .table tbody tr:hover {        .table tbody tr:hover {

            background: rgba(212, 175, 55, 0.05);            background: rgba(212, 175, 55, 0.05);

            transform: scale(1.01);            transform: scale(1.01);

        }        }



        /* === LUXURY BADGES === */        /* === LUXURY BADGES === */

        .badge {        .badge {

            font-weight: 500;            font-weight: 500;

            padding: 0.5rem 1rem;            padding: 0.5rem 1rem;

            border-radius: 2rem;            border-radius: 2rem;

            text-transform: uppercase;            text-transform: uppercase;

            letter-spacing: 0.05em;            letter-spacing: 0.05em;

            font-size: 0.75rem;            font-size: 0.75rem;

        }        }



        .badge.bg-primary {        .badge.bg-primary {

            background: var(--gradient-gold) !important;            background: var(--gradient-gold) !important;

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

        }        }



        .badge.bg-success {        .badge.bg-success {

            background: linear-gradient(135deg, var(--luxmoto-success) 0%, #10B981 100%) !important;            background: linear-gradient(135deg, var(--luxmoto-success) 0%, #10B981 100%) !important;

        }        }



        .badge.bg-warning {        .badge.bg-warning {

            background: linear-gradient(135deg, var(--luxmoto-warning) 0%, #F59E0B 100%) !important;            background: linear-gradient(135deg, var(--luxmoto-warning) 0%, #F59E0B 100%) !important;

            color: var(--luxmoto-white);            color: var(--luxmoto-white);

        }        }



        .badge.bg-danger {        .badge.bg-danger {

            background: linear-gradient(135deg, var(--luxmoto-danger) 0%, #EF4444 100%) !important;            background: linear-gradient(135deg, var(--luxmoto-danger) 0%, #EF4444 100%) !important;

        }        }



        .badge.bg-info {        .badge.bg-info {

            background: linear-gradient(135deg, var(--luxmoto-info) 0%, #3B82F6 100%) !important;            background: linear-gradient(135deg, var(--luxmoto-info) 0%, #3B82F6 100%) !important;

        }        }



        .badge.bg-secondary {        .badge.bg-secondary {

            background: var(--gradient-surface) !important;            background: var(--gradient-surface) !important;

        }        }



        /* === LUXURY ALERTS === */        /* === LUXURY ALERTS === */

        .alert {        .alert {

            border: none;            border: none;

            border-radius: 1rem;            border-radius: 1rem;

            padding: 1.25rem 1.5rem;            padding: 1.25rem 1.5rem;

            position: relative;            position: relative;

            overflow: hidden;            overflow: hidden;

            backdrop-filter: blur(10px);            backdrop-filter: blur(10px);

        }        }



        .alert::before {        .alert::before {

            content: '';            content: '';

            position: absolute;            position: absolute;

            left: 0;            left: 0;

            top: 0;            top: 0;

            width: 4px;            width: 4px;

            height: 100%;            height: 100%;

            background: var(--luxmoto-gold-primary);            background: var(--luxmoto-gold-primary);

        }        }



        .alert-success {        .alert-success {

            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1) 0%, rgba(16, 185, 129, 0.05) 100%);

            color: var(--luxmoto-success);            color: var(--luxmoto-success);

        }        }



        .alert-success::before {        .alert-success::before {

            background: var(--luxmoto-success);            background: var(--luxmoto-success);

        }        }



        .alert-warning {        .alert-warning {

            background: linear-gradient(135deg, rgba(217, 119, 6, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);            background: linear-gradient(135deg, rgba(217, 119, 6, 0.1) 0%, rgba(245, 158, 11, 0.05) 100%);

            color: var(--luxmoto-warning);            color: var(--luxmoto-warning);

        }        }



        .alert-warning::before {        .alert-warning::before {

            background: var(--luxmoto-warning);            background: var(--luxmoto-warning);

        }        }



        .alert-danger {        .alert-danger {

            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);            background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(239, 68, 68, 0.05) 100%);

            color: var(--luxmoto-danger);            color: var(--luxmoto-danger);

        }        }



        .alert-danger::before {        .alert-danger::before {

            background: var(--luxmoto-danger);            background: var(--luxmoto-danger);

        }        }



        .alert-info {        .alert-info {

            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);            background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);

            color: var(--luxmoto-info);            color: var(--luxmoto-info);

        }        }



        .alert-info::before {        .alert-info::before {

            background: var(--luxmoto-info);            background: var(--luxmoto-info);

        }        }



        /* === LUXURY PAGINATION === */        /* === LUXURY PAGINATION === */

        .pagination .page-link {        .pagination .page-link {

            border: 2px solid var(--luxmoto-light-gray);            border: 2px solid var(--luxmoto-light-gray);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            background: rgba(255, 255, 255, 0.9);            background: rgba(255, 255, 255, 0.9);

            backdrop-filter: blur(10px);            backdrop-filter: blur(10px);

            margin: 0 0.25rem;            margin: 0 0.25rem;

            border-radius: 0.75rem;            border-radius: 0.75rem;

            padding: 0.75rem 1rem;            padding: 0.75rem 1rem;

            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

        }        }



        .pagination .page-link:hover {        .pagination .page-link:hover {

            background: var(--luxmoto-gold-light);            background: var(--luxmoto-gold-light);

            border-color: var(--luxmoto-gold-primary);            border-color: var(--luxmoto-gold-primary);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            transform: translateY(-2px);            transform: translateY(-2px);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        .pagination .page-item.active .page-link {        .pagination .page-item.active .page-link {

            background: var(--gradient-gold);            background: var(--gradient-gold);

            border-color: var(--luxmoto-gold-primary);            border-color: var(--luxmoto-gold-primary);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            box-shadow: var(--shadow-md);            box-shadow: var(--shadow-md);

        }        }



        /* === LUXURY DROPDOWNS === */        /* === LUXURY DROPDOWNS === */

        .dropdown-menu {        .dropdown-menu {

            background: rgba(255, 255, 255, 0.95);            background: rgba(255, 255, 255, 0.95);

            backdrop-filter: blur(20px);            backdrop-filter: blur(20px);

            border: 1px solid rgba(212, 175, 55, 0.2);            border: 1px solid rgba(212, 175, 55, 0.2);

            border-radius: 1rem;            border-radius: 1rem;

            box-shadow: var(--shadow-luxury);            box-shadow: var(--shadow-luxury);

            padding: 1rem 0;            padding: 1rem 0;

            margin-top: 0.5rem;            margin-top: 0.5rem;

        }        }



        .dropdown-item {        .dropdown-item {

            padding: 0.75rem 1.5rem;            padding: 0.75rem 1.5rem;

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            transition: all 0.3s ease;            transition: all 0.3s ease;

        }        }



        .dropdown-item:hover {        .dropdown-item:hover {

            background: rgba(212, 175, 55, 0.1);            background: rgba(212, 175, 55, 0.1);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            transform: translateX(4px);            transform: translateX(4px);

        }        }



        /* === LUXURY MODAL === */        /* === LUXURY MODAL === */

        .modal-content {        .modal-content {

            border: none;            border: none;

            border-radius: 1.5rem;            border-radius: 1.5rem;

            backdrop-filter: blur(20px);            backdrop-filter: blur(20px);

            background: rgba(255, 255, 255, 0.95);            background: rgba(255, 255, 255, 0.95);

            box-shadow: var(--shadow-luxury);            box-shadow: var(--shadow-luxury);

        }        }



        .modal-header {        .modal-header {

            background: var(--gradient-gold);            background: var(--gradient-gold);

            color: var(--luxmoto-primary);            color: var(--luxmoto-primary);

            border-radius: 1.5rem 1.5rem 0 0;            border-radius: 1.5rem 1.5rem 0 0;

            padding: 1.5rem 2rem;            padding: 1.5rem 2rem;

            border-bottom: none;            border-bottom: none;

        }        }



        .modal-title {        .modal-title {

            font-family: var(--font-display);            font-family: var(--font-display);

            font-weight: 600;            font-weight: 600;

        }        }



        .modal-body {        .modal-body {

            padding: 2rem;            padding: 2rem;

        }        }



        .modal-footer {        .modal-footer {

            border-top: 1px solid rgba(212, 175, 55, 0.2);            border-top: 1px solid rgba(212, 175, 55, 0.2);

            padding: 1.5rem 2rem;            padding: 1.5rem 2rem;

        }        }



        /* === RESPONSIVE LUXURY === */        /* === RESPONSIVE LUXURY === */

        @media (max-width: 768px) {        @media (max-width: 768px) {

            .sidebar {            .sidebar {

                transform: translateX(-100%);                transform: translateX(-100%);

                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);

                position: fixed;                position: fixed;

                z-index: 1000;                z-index: 1000;

                width: 280px;                width: 280px;

            }            }



            .sidebar.show {            .sidebar.show {

                transform: translateX(0);                transform: translateX(0);

            }            }



            .content {            .content {

                margin-left: 0 !important;                margin-left: 0 !important;

            }            }



            .navbar {            .navbar {

                padding: 1rem;                padding: 1rem;

            }            }



            .stat-number {            .stat-number {

                font-size: 2rem;                font-size: 2rem;

            }            }



            .card-body {            .card-body {

                padding: 1rem;                padding: 1rem;

            }            }



            .btn {            .btn {

                padding: 0.625rem 1.25rem;                padding: 0.625rem 1.25rem;

            }            }

        }        }



        /* === LUXURY ANIMATIONS === */        /* === LUXURY ANIMATIONS === */

        @keyframes luxuryFadeIn {        @keyframes luxuryFadeIn {

            from {            from {

                opacity: 0;                opacity: 0;

                transform: translateY(20px);                transform: translateY(20px);

            }            }

            to {            to {

                opacity: 1;                opacity: 1;

                transform: translateY(0);                transform: translateY(0);

            }            }

        }        }



        @keyframes goldShimmer {        @keyframes goldShimmer {

            0% {            0% {

                background-position: -200% center;                background-position: -200% center;

            }            }

            100% {            100% {

                background-position: 200% center;                background-position: 200% center;

            }            }

        }        }



        .luxury-fade-in {        .luxury-fade-in {

            animation: luxuryFadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);            animation: luxuryFadeIn 0.6s cubic-bezier(0.4, 0, 0.2, 1);

        }        }



        .gold-shimmer {        .gold-shimmer {

            background: linear-gradient(90deg, var(--luxmoto-gold-primary) 25%, var(--luxmoto-gold-light) 50%, var(--luxmoto-gold-primary) 75%);            background: linear-gradient(90deg, var(--luxmoto-gold-primary) 25%, var(--luxmoto-gold-light) 50%, var(--luxmoto-gold-primary) 75%);

            background-size: 200% auto;            background-size: 200% auto;

            -webkit-background-clip: text;            -webkit-background-clip: text;

            -webkit-text-fill-color: transparent;            -webkit-text-fill-color: transparent;

            animation: goldShimmer 3s infinite linear;            animation: goldShimmer 3s infinite linear;

        }        }



        /* === LUXURY SCROLLBAR === */        /* === LUXURY SCROLLBAR === */

        ::-webkit-scrollbar {        ::-webkit-scrollbar {

            width: 8px;            width: 8px;

        }        }



        ::-webkit-scrollbar-track {        ::-webkit-scrollbar-track {

            background: var(--luxmoto-light-gray);            background: var(--luxmoto-light-gray);

            border-radius: 4px;            border-radius: 4px;

        }        }



        ::-webkit-scrollbar-thumb {        ::-webkit-scrollbar-thumb {

            background: var(--gradient-gold);            background: var(--gradient-gold);

            border-radius: 4px;            border-radius: 4px;

            transition: all 0.3s ease;            transition: all 0.3s ease;

        }        }



        ::-webkit-scrollbar-thumb:hover {        ::-webkit-scrollbar-thumb:hover {

            background: var(--luxmoto-gold-dark);            background: var(--luxmoto-gold-dark);

        }        }



        /* === UTILITY CLASSES === */        /* === UTILITY CLASSES === */

        .text-luxury-primary { color: var(--luxmoto-primary) !important; }        .text-luxury-primary { color: var(--luxmoto-primary) !important; }

        .text-luxury-gold { color: var(--luxmoto-gold-primary) !important; }        .text-luxury-gold { color: var(--luxmoto-gold-primary) !important; }

        .text-luxury-white { color: var(--luxmoto-white) !important; }        .text-luxury-white { color: var(--luxmoto-white) !important; }

        .bg-luxury-gradient { background: var(--gradient-gold) !important; }        .bg-luxury-gradient { background: var(--gradient-gold) !important; }

        .bg-luxury-surface { background: var(--gradient-surface) !important; }        .bg-luxury-surface { background: var(--gradient-surface) !important; }

        .shadow-luxury { box-shadow: var(--shadow-luxury) !important; }        .shadow-luxury { box-shadow: var(--shadow-luxury) !important; }

        .border-luxury-gold { border-color: var(--luxmoto-gold-primary) !important; }        .border-luxury-gold { border-color: var(--luxmoto-gold-primary) !important; }



        /* === LOADING SPINNER === */        /* === LOADING SPINNER === */

        .luxury-spinner {        .luxury-spinner {

            width: 40px;            width: 40px;

            height: 40px;            height: 40px;

            border: 3px solid rgba(212, 175, 55, 0.3);            border: 3px solid rgba(212, 175, 55, 0.3);

            border-top: 3px solid var(--luxmoto-gold-primary);            border-top: 3px solid var(--luxmoto-gold-primary);

            border-radius: 50%;            border-radius: 50%;

            animation: spin 1s linear infinite;            animation: spin 1s linear infinite;

        }        }



        @keyframes spin {        @keyframes spin {

            0% { transform: rotate(0deg); }            0% { transform: rotate(0deg); }

            100% { transform: rotate(360deg); }        }

        }

    </style>        ::-webkit-scrollbar-thumb:hover {

    @stack('styles')            background: var(--luxmoto-gold-dark);

</head>        }

<body>

    <div class="d-flex">        /* === UTILITY CLASSES === */

        <!-- === LUXURY SIDEBAR === -->        .text-luxury-primary { color: var(--luxmoto-primary) !important; }

        <nav class="sidebar">        .text-luxury-gold { color: var(--luxmoto-gold-primary) !important; }

            <div class="brand-section">        .text-luxury-white { color: var(--luxmoto-white) !important; }

                <h1 class="brand-title gold-shimmer">LuxuryMoto</h1>        .bg-luxury-gradient { background: var(--gradient-gold) !important; }

                <p class="brand-subtitle">Premium Motor Rental</p>        .bg-luxury-surface { background: var(--gradient-surface) !important; }

                <div class="mt-3">        .shadow-luxury { box-shadow: var(--shadow-luxury) !important; }

                    <div class="d-flex align-items-center justify-content-center">        .border-luxury-gold { border-color: var(--luxmoto-gold-primary) !important; }

                        <div class="bg-luxury-gradient rounded-circle p-2 me-2">

                            <i class="bi bi-person-circle text-luxury-primary fs-5"></i>        /* === LOADING SPINNER === */

                        </div>        .luxury-spinner {

                        <div class="text-start">            width: 40px;

                            <div class="text-luxury-white fw-semibold">{{ Auth::user()->name }}</div>            height: 40px;

                            <small class="text-luxury-gold text-uppercase">{{ Auth::user()->role->value }}</small>            border: 3px solid rgba(212, 175, 55, 0.3);

                        </div>            border-top: 3px solid var(--luxmoto-gold-primary);

                    </div>            border-radius: 50%;

                </div>            animation: spin 1s linear infinite;

            </div>        }



            <div class="nav-menu">        @keyframes spin {

                @if(Auth::user()->role->value === 'admin')            0% { transform: rotate(0deg); }

                    <!-- Admin Navigation -->            100% { transform: rotate(360deg); }

                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">        }

                        <i class="bi bi-speedometer2"></i>    </style>

                        <span>Dashboard</span>    @stack('styles')

                    </a></head>

                    <a href="{{ route('admin.motors.index') }}" class="nav-link {{ request()->routeIs('admin.motors.*') ? 'active' : '' }}"><body>

                        <i class="bi bi-motorcycle"></i>    <div class="d-flex">

                        <span>Kelola Motor</span>        <!-- === LUXURY SIDEBAR === -->

                    </a>        <nav class="sidebar">

                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">            <div class="brand-section">

                        <i class="bi bi-people"></i>                <h1 class="brand-title gold-shimmer">LuxuryMoto</h1>

                        <span>Kelola Pengguna</span>                <p class="brand-subtitle">Premium Motor Rental</p>

                    </a>                <div class="mt-3">

                    <a href="{{ route('admin.penyewaans.index') }}" class="nav-link {{ request()->routeIs('admin.penyewaans.*') ? 'active' : '' }}">                    <div class="d-flex align-items-center justify-content-center">

                        <i class="bi bi-calendar-check"></i>                        <div class="bg-luxury-gradient rounded-circle p-2 me-2">

                        <span>Data Penyewaan</span>                            <i class="bi bi-person-circle text-luxury-primary fs-5"></i>

                    </a>                        </div>

                    <a href="{{ route('admin.motors-verification.index') }}" class="nav-link {{ request()->routeIs('admin.motors-verification.*') ? 'active' : '' }}">                        <div class="text-start">

                        <i class="bi bi-shield-check"></i>                            <div class="text-luxury-white fw-semibold">{{ Auth::user()->name }}</div>

                        <span>Verifikasi Motor</span>                            <small class="text-luxury-gold text-uppercase">{{ Auth::user()->role->value }}</small>

                    </a>                        </div>

                @elseif(Auth::user()->role->value === 'pemilik')                    </div>

                    <!-- Owner Navigation -->                </div>

                    <a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">            </div>

                        <i class="bi bi-speedometer2"></i>

                        <span>Dashboard</span>            <div class="nav-menu">

                    </a>                @if(Auth::user()->role->value === 'admin')

                    <a href="{{ route('owner.motors.index') }}" class="nav-link {{ request()->routeIs('owner.motors.*') ? 'active' : '' }}">                    <!-- Admin Navigation -->

                        <i class="bi bi-motorcycle"></i>                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                        <span>Motor Saya</span>                        <i class="bi bi-speedometer2"></i>

                    </a>                        <span>Dashboard</span>

                    <a href="{{ route('owner.penyewaans.index') }}" class="nav-link {{ request()->routeIs('owner.penyewaans.*') ? 'active' : '' }}">                    </a>

                        <i class="bi bi-calendar-event"></i>                    <a href="{{ route('admin.motors.index') }}" class="nav-link {{ request()->routeIs('admin.motors.*') ? 'active' : '' }}">

                        <span>Penyewaan</span>                        <i class="bi bi-motorcycle"></i>

                    </a>                        <span>Kelola Motor</span>

                    <a href="{{ route('owner.bagi-hasil.index') }}" class="nav-link {{ request()->routeIs('owner.bagi-hasil.*') ? 'active' : '' }}">                    </a>

                        <i class="bi bi-pie-chart"></i>                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">

                        <span>Bagi Hasil</span>                        <i class="bi bi-people"></i>

                    </a>                        <span>Kelola Pengguna</span>

                @else                    </a>

                    <!-- Renter Navigation -->                    <a href="{{ route('admin.penyewaans.index') }}" class="nav-link {{ request()->routeIs('admin.penyewaans.*') ? 'active' : '' }}">

                    <a href="{{ route('renter.dashboard') }}" class="nav-link {{ request()->routeIs('renter.dashboard') ? 'active' : '' }}">                        <i class="bi bi-calendar-check"></i>

                        <i class="bi bi-speedometer2"></i>                        <span>Data Penyewaan</span>

                        <span>Dashboard</span>                    </a>

                    </a>                    <a href="{{ route('admin.motors-verification.index') }}" class="nav-link {{ request()->routeIs('admin.motors-verification.*') ? 'active' : '' }}">

                    <a href="{{ route('renter.motors.index') }}" class="nav-link {{ request()->routeIs('renter.motors.*') ? 'active' : '' }}">                        <i class="bi bi-shield-check"></i>

                        <i class="bi bi-search"></i>                        <span>Verifikasi Motor</span>

                        <span>Cari Motor</span>                    </a>

                    </a>                @elseif(Auth::user()->role->value === 'pemilik')

                    <a href="{{ route('renter.penyewaans.index') }}" class="nav-link {{ request()->routeIs('renter.penyewaans.*') ? 'active' : '' }}">                    <!-- Owner Navigation -->

                        <i class="bi bi-calendar-check"></i>                    <a href="{{ route('owner.dashboard') }}" class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">

                        <span>Penyewaan Saya</span>                        <i class="bi bi-speedometer2"></i>

                    </a>                        <span>Dashboard</span>

                    <a href="{{ route('renter.transaksi.index') }}" class="nav-link {{ request()->routeIs('renter.transaksi.*') ? 'active' : '' }}">                    </a>

                        <i class="bi bi-credit-card"></i>                    <a href="{{ route('owner.motors.index') }}" class="nav-link {{ request()->routeIs('owner.motors.*') ? 'active' : '' }}">

                        <span>Riwayat Transaksi</span>                        <i class="bi bi-motorcycle"></i>

                    </a>                        <span>Motor Saya</span>

                @endif                    </a>

                    <a href="{{ route('owner.penyewaans.index') }}" class="nav-link {{ request()->routeIs('owner.penyewaans.*') ? 'active' : '' }}">

                <!-- Logout -->                        <i class="bi bi-calendar-event"></i>

                <div class="mt-auto pt-4" style="border-top: 1px solid rgba(212, 175, 55, 0.1);">                        <span>Penyewaan</span>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">                    </a>

                        @csrf                    <a href="{{ route('owner.bagi-hasil.index') }}" class="nav-link {{ request()->routeIs('owner.bagi-hasil.*') ? 'active' : '' }}">

                        <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">                        <i class="bi bi-pie-chart"></i>

                            <i class="bi bi-box-arrow-right"></i>                        <span>Bagi Hasil</span>

                            <span>Logout</span>                    </a>

                        </button>                @else

                    </form>                    <!-- Renter Navigation -->

                </div>                    <a href="{{ route('renter.dashboard') }}" class="nav-link {{ request()->routeIs('renter.dashboard') ? 'active' : '' }}">

            </div>                        <i class="bi bi-speedometer2"></i>

        </nav>                        <span>Dashboard</span>

                    </a>

        <!-- === LUXURY CONTENT AREA === -->                    <a href="{{ route('renter.motors.index') }}" class="nav-link {{ request()->routeIs('renter.motors.*') ? 'active' : '' }}">

        <main class="content flex-grow-1">                        <i class="bi bi-search"></i>

            <!-- Premium Top Navigation -->                        <span>Cari Motor</span>

            <nav class="navbar">                    </a>

                <div class="container-fluid">                    <a href="{{ route('renter.penyewaans.index') }}" class="nav-link {{ request()->routeIs('renter.penyewaans.*') ? 'active' : '' }}">

                    <div class="d-flex align-items-center">                        <i class="bi bi-calendar-check"></i>

                        <button class="btn btn-link d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">                        <span>Penyewaan Saya</span>

                            <i class="bi bi-list text-luxury-primary fs-4"></i>                    </a>

                        </button>                    <a href="{{ route('renter.transaksi.index') }}" class="nav-link {{ request()->routeIs('renter.transaksi.*') ? 'active' : '' }}">

                        <div class="d-flex align-items-center">                        <i class="bi bi-credit-card"></i>

                            <div class="bg-luxury-gradient rounded-circle p-2 me-3">                        <span>Riwayat Transaksi</span>

                                <i class="bi bi-geo-alt text-luxury-primary"></i>                    </a>

                            </div>                @endif

                            <div>

                                <div class="text-luxury-primary fw-semibold">Premium Location</div>                <!-- Logout -->

                                <small class="text-muted">Jakarta, Indonesia</small>                <div class="mt-auto pt-4" style="border-top: 1px solid rgba(212, 175, 55, 0.1);">

                            </div>                    <form method="POST" action="{{ route('logout') }}" class="d-inline">

                        </div>                        @csrf

                    </div>                        <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">

                                                <i class="bi bi-box-arrow-right"></i>

                    <div class="d-flex align-items-center">                            <span>Logout</span>

                        <!-- Search -->                        </button>

                        <div class="me-3">                    </form>

                            <div class="input-group">                </div>

                                <span class="input-group-text bg-transparent border-luxury-gold">            </div>

                                    <i class="bi bi-search text-luxury-gold"></i>        </nav>

                                </span>

                                <input type="search" class="form-control border-luxury-gold" placeholder="Cari..." style="max-width: 200px;">        <!-- === LUXURY CONTENT AREA === -->

                            </div>        <main class="content flex-grow-1">

                        </div>            <!-- Premium Top Navigation -->

            <nav class="navbar">

                        <!-- Notifications -->                <div class="container-fluid">

                        <div class="dropdown me-3">                    <div class="d-flex align-items-center">

                            <button class="btn btn-link position-relative p-2" type="button" data-bs-toggle="dropdown">                        <button class="btn btn-link d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">

                                <i class="bi bi-bell text-luxury-primary fs-5"></i>                            <i class="bi bi-list text-luxury-primary fs-4"></i>

                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>                        </button>

                            </button>                        <div class="d-flex align-items-center">

                            <ul class="dropdown-menu dropdown-menu-end">                            <div class="bg-luxury-gradient rounded-circle p-2 me-3">

                                <li><h6 class="dropdown-header">Notifikasi Terbaru</h6></li>                                <i class="bi bi-geo-alt text-luxury-primary"></i>

                                <li><a class="dropdown-item" href="#"><i class="bi bi-info-circle me-2"></i>Motor baru tersedia</a></li>                            </div>

                                <li><a class="dropdown-item" href="#"><i class="bi bi-check-circle me-2"></i>Pembayaran berhasil</a></li>                            <div>

                                <li><a class="dropdown-item" href="#"><i class="bi bi-exclamation-triangle me-2"></i>Reminder pengembalian</a></li>                                <div class="text-luxury-primary fw-semibold">Premium Location</div>

                            </ul>                                <small class="text-muted">Jakarta, Indonesia</small>

                        </div>                            </div>

                        </div>

                        <!-- User Menu -->                    </div>

                        <div class="dropdown">                    

                            <button class="btn btn-link p-2" type="button" data-bs-toggle="dropdown">                    <div class="d-flex align-items-center">

                                <div class="bg-luxury-gradient rounded-circle p-2">                        <!-- Search -->

                                    <i class="bi bi-person-circle text-luxury-primary fs-5"></i>                        <div class="me-3">

                                </div>                            <div class="input-group">

                            </button>                                <span class="input-group-text bg-transparent border-luxury-gold">

                            <ul class="dropdown-menu dropdown-menu-end">                                    <i class="bi bi-search text-luxury-gold"></i>

                                <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>                                </span>

                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>                                <input type="search" class="form-control border-luxury-gold" placeholder="Cari..." style="max-width: 200px;">

                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>                            </div>

                                <li><hr class="dropdown-divider"></li>                        </div>

                                <li>

                                    <form method="POST" action="{{ route('logout') }}">                        <!-- Notifications -->

                                        @csrf                        <div class="dropdown me-3">

                                        <button type="submit" class="dropdown-item">                            <button class="btn btn-link position-relative p-2" type="button" data-bs-toggle="dropdown">

                                            <i class="bi bi-box-arrow-right me-2"></i>Logout                                <i class="bi bi-bell text-luxury-primary fs-5"></i>

                                        </button>                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">3</span>

                                    </form>                            </button>

                                </li>                            <ul class="dropdown-menu dropdown-menu-end">

                            </ul>                                <li><h6 class="dropdown-header">Notifikasi Terbaru</h6></li>

                        </div>                                <li><a class="dropdown-item" href="#"><i class="bi bi-info-circle me-2"></i>Motor baru tersedia</a></li>

                    </div>                                <li><a class="dropdown-item" href="#"><i class="bi bi-check-circle me-2"></i>Pembayaran berhasil</a></li>

                </div>                                <li><a class="dropdown-item" href="#"><i class="bi bi-exclamation-triangle me-2"></i>Reminder pengembalian</a></li>

            </nav>                            </ul>

                        </div>

            <!-- Page Content -->

            <div class="container-fluid px-4 py-4">                        <!-- User Menu -->

                <!-- Page Header -->                        <div class="dropdown">

                @if(isset($pageTitle))                            <button class="btn btn-link p-2" type="button" data-bs-toggle="dropdown">

                <div class="d-flex justify-content-between align-items-center mb-4">                                <div class="bg-luxury-gradient rounded-circle p-2">

                    <div>                                    <i class="bi bi-person-circle text-luxury-primary fs-5"></i>

                        <h2 class="text-luxury-primary fw-bold mb-1">{{ $pageTitle }}</h2>                                </div>

                        @if(isset($pageSubtitle))                            </button>

                            <p class="text-muted mb-0">{{ $pageSubtitle }}</p>                            <ul class="dropdown-menu dropdown-menu-end">

                        @endif                                <li><h6 class="dropdown-header">{{ Auth::user()->name }}</h6></li>

                    </div>                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>

                    @if(isset($pageActions))                                <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>

                        <div class="d-flex gap-2">                                <li><hr class="dropdown-divider"></li>

                            {!! $pageActions !!}                                <li>

                        </div>                                    <form method="POST" action="{{ route('logout') }}">

                    @endif                                        @csrf

                </div>                                        <button type="submit" class="dropdown-item">

                @endif                                            <i class="bi bi-box-arrow-right me-2"></i>Logout

                                        </button>

                <!-- Flash Messages -->                                    </form>

                @if(session('success'))                                </li>

                    <div class="alert alert-success alert-dismissible fade show luxury-fade-in" role="alert">                            </ul>

                        <i class="bi bi-check-circle-fill me-2"></i>                        </div>

                        {{ session('success') }}                    </div>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>                </div>

                    </div>            </nav>

                @endif

            <!-- Page Content -->

                @if(session('error'))            <div class="container-fluid px-4 py-4">

                    <div class="alert alert-danger alert-dismissible fade show luxury-fade-in" role="alert">                <!-- Page Header -->

                        <i class="bi bi-exclamation-triangle-fill me-2"></i>                @if(isset($pageTitle))

                        {{ session('error') }}                <div class="d-flex justify-content-between align-items-center mb-4">

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>                    <div>

                    </div>                        <h2 class="text-luxury-primary fw-bold mb-1">{{ $pageTitle }}</h2>

                @endif                        @if(isset($pageSubtitle))

                            <p class="text-muted mb-0">{{ $pageSubtitle }}</p>

                @if(session('warning'))                        @endif

                    <div class="alert alert-warning alert-dismissible fade show luxury-fade-in" role="alert">                    </div>

                        <i class="bi bi-exclamation-triangle-fill me-2"></i>                    @if(isset($pageActions))

                        {{ session('warning') }}                        <div class="d-flex gap-2">

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>                            {!! $pageActions !!}

                    </div>                        </div>

                @endif                    @endif

                </div>

                @if(session('info'))                @endif

                    <div class="alert alert-info alert-dismissible fade show luxury-fade-in" role="alert">

                        <i class="bi bi-info-circle-fill me-2"></i>                <!-- Flash Messages -->

                        {{ session('info') }}                @if(session('success'))

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>                    <div class="alert alert-success alert-dismissible fade show luxury-fade-in" role="alert">

                    </div>                        <i class="bi bi-check-circle-fill me-2"></i>

                @endif                        {{ session('success') }}

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                <!-- Main Content -->                    </div>

                <div class="luxury-fade-in">                @endif

                    @yield('content')

                </div>                @if(session('error'))

            </div>                    <div class="alert alert-danger alert-dismissible fade show luxury-fade-in" role="alert">

                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

            <!-- Footer -->                        {{ session('error') }}

            <footer class="mt-auto py-4" style="background: linear-gradient(135deg, #FAFAFA 0%, #F8F9FA 100%); border-top: 1px solid rgba(212, 175, 55, 0.1);">                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                <div class="container-fluid px-4">                    </div>

                    <div class="row align-items-center">                @endif

                        <div class="col-md-6">

                            <div class="d-flex align-items-center">                @if(session('warning'))

                                <div class="bg-luxury-gradient rounded-circle p-2 me-3">                    <div class="alert alert-warning alert-dismissible fade show luxury-fade-in" role="alert">

                                    <i class="bi bi-motorcycle text-luxury-primary"></i>                        <i class="bi bi-exclamation-triangle-fill me-2"></i>

                                </div>                        {{ session('warning') }}

                                <div>                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                                    <div class="fw-semibold text-luxury-primary">LuxuryMoto</div>                    </div>

                                    <small class="text-muted">Premium Motor Rental Service</small>                @endif

                                </div>

                            </div>                @if(session('info'))

                        </div>                    <div class="alert alert-info alert-dismissible fade show luxury-fade-in" role="alert">

                        <div class="col-md-6 text-end">                        <i class="bi bi-info-circle-fill me-2"></i>

                            <small class="text-muted">© {{ date('Y') }} LuxuryMoto. Sistem Penyewaan Motor Premium.</small>                        {{ session('info') }}

                        </div>                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                    </div>                    </div>

                </div>                @endif

            </footer>

        </main>                <!-- Main Content -->

    </div>                <div class="luxury-fade-in">

                    @yield('content')

    <!-- Bootstrap JS -->                </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>            </div>

    

    <!-- Custom Luxury Scripts -->            <!-- Footer -->

    <script>            <footer class="mt-auto py-4" style="background: linear-gradient(135deg, #FAFAFA 0%, #F8F9FA 100%); border-top: 1px solid rgba(212, 175, 55, 0.1);">

        // Luxury Loading Effect                <div class="container-fluid px-4">

        document.addEventListener('DOMContentLoaded', function() {                    <div class="row align-items-center">

            // Add luxury fade-in animation to cards                        <div class="col-md-6">

            const cards = document.querySelectorAll('.card');                            <div class="d-flex align-items-center">

            cards.forEach((card, index) => {                                <div class="bg-luxury-gradient rounded-circle p-2 me-3">

                card.style.animationDelay = `${index * 0.1}s`;                                    <i class="bi bi-motorcycle text-luxury-primary"></i>

                card.classList.add('luxury-fade-in');                                </div>

            });                                <div>

                                    <div class="fw-semibold text-luxury-primary">LuxuryMoto</div>

            // Smooth scrolling for sidebar links                                    <small class="text-muted">Premium Motor Rental Service</small>

            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');                                </div>

            sidebarLinks.forEach(link => {                            </div>

                link.addEventListener('click', function(e) {                        </div>

                    // Add loading effect                        <div class="col-md-6 text-end">

                    if (!this.classList.contains('active')) {                            <small class="text-muted">© {{ date('Y') }} LuxuryMoto. Sistem Penyewaan Motor Premium.</small>

                        this.style.opacity = '0.7';                        </div>

                        this.style.transform = 'scale(0.98)';                    </div>

                    }                </div>

                });            </footer>

            });        </main>

    </div>

            // Auto-hide alerts after 5 seconds

            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');    <!-- Bootstrap JS -->

            alerts.forEach(alert => {    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

                setTimeout(() => {    

                    const bsAlert = new bootstrap.Alert(alert);    <!-- Custom Luxury Scripts -->

                    bsAlert.close();    <script>

                }, 5000);        // Luxury Loading Effect

            });        document.addEventListener('DOMContentLoaded', function() {

            // Add luxury fade-in animation to cards

            // Luxury hover effects for stat cards            const cards = document.querySelectorAll('.card');

            const statCards = document.querySelectorAll('.stat-card');            cards.forEach((card, index) => {

            statCards.forEach(card => {                card.style.animationDelay = `${index * 0.1}s`;

                card.addEventListener('mouseenter', function() {                card.classList.add('luxury-fade-in');

                    this.style.transform = 'translateY(-8px) scale(1.02)';            });

                });

                card.addEventListener('mouseleave', function() {            // Smooth scrolling for sidebar links

                    this.style.transform = 'translateY(0) scale(1)';            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');

                });            sidebarLinks.forEach(link => {

            });                link.addEventListener('click', function(e) {

                    // Add loading effect

            // Enhanced table row hover effects                    if (!this.classList.contains('active')) {

            const tableRows = document.querySelectorAll('.table tbody tr');                        this.style.opacity = '0.7';

            tableRows.forEach(row => {                        this.style.transform = 'scale(0.98)';

                row.addEventListener('mouseenter', function() {                    }

                    this.style.boxShadow = '0 4px 12px rgba(212, 175, 55, 0.15)';                });

                });            });

                row.addEventListener('mouseleave', function() {

                    this.style.boxShadow = 'none';            // Auto-hide alerts after 5 seconds

                });            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');

            });            alerts.forEach(alert => {

        });                setTimeout(() => {

                    const bsAlert = new bootstrap.Alert(alert);

        // Luxury Form Validation                    bsAlert.close();

        function addLuxuryValidation() {                }, 5000);

            const forms = document.querySelectorAll('form');            });

            forms.forEach(form => {

                form.addEventListener('submit', function(e) {            // Luxury hover effects for stat cards

                    const requiredFields = form.querySelectorAll('[required]');            const statCards = document.querySelectorAll('.stat-card');

                    let isValid = true;            statCards.forEach(card => {

                card.addEventListener('mouseenter', function() {

                    requiredFields.forEach(field => {                    this.style.transform = 'translateY(-8px) scale(1.02)';

                        if (!field.value.trim()) {                });

                            field.classList.add('is-invalid');                card.addEventListener('mouseleave', function() {

                            field.style.borderColor = '#DC2626';                    this.style.transform = 'translateY(0) scale(1)';

                            isValid = false;                });

                        } else {            });

                            field.classList.remove('is-invalid');

                            field.style.borderColor = '#D4AF37';            // Enhanced table row hover effects

                        }            const tableRows = document.querySelectorAll('.table tbody tr');

                    });            tableRows.forEach(row => {

                row.addEventListener('mouseenter', function() {

                    if (!isValid) {                    this.style.boxShadow = '0 4px 12px rgba(212, 175, 55, 0.15)';

                        e.preventDefault();                });

                        // Show luxury error notification                row.addEventListener('mouseleave', function() {

                        showLuxuryNotification('Mohon lengkapi semua field yang diperlukan', 'error');                    this.style.boxShadow = 'none';

                    }                });

                });            });

            });        });

        }

        // Luxury Form Validation

        // Luxury Notification System        function addLuxuryValidation() {

        function showLuxuryNotification(message, type = 'info') {            const forms = document.querySelectorAll('form');

            const notification = document.createElement('div');            forms.forEach(form => {

            notification.className = `alert alert-${type} position-fixed top-0 end-0 m-3 luxury-fade-in`;                form.addEventListener('submit', function(e) {

            notification.style.zIndex = '9999';                    const requiredFields = form.querySelectorAll('[required]');

            notification.style.minWidth = '300px';                    let isValid = true;

            notification.innerHTML = `

                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'error' ? 'exclamation-triangle-fill' : 'info-circle-fill'} me-2"></i>                    requiredFields.forEach(field => {

                ${message}                        if (!field.value.trim()) {

                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>                            field.classList.add('is-invalid');

            `;                            field.style.borderColor = '#DC2626';

            document.body.appendChild(notification);                            isValid = false;

                        } else {

            // Auto-remove after 4 seconds                            field.classList.remove('is-invalid');

            setTimeout(() => {                            field.style.borderColor = '#D4AF37';

                notification.remove();                        }

            }, 4000);                    });

        }

                    if (!isValid) {

        // Initialize luxury features                        e.preventDefault();

        document.addEventListener('DOMContentLoaded', function() {                        // Show luxury error notification

            addLuxuryValidation();                        showLuxuryNotification('Mohon lengkapi semua field yang diperlukan', 'error');

        });                    }

    </script>                });

                });

    @stack('scripts')        }

</body>

</html>        // Luxury Notification System
        function showLuxuryNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed top-0 end-0 m-3 luxury-fade-in`;
            notification.style.zIndex = '9999';
            notification.style.minWidth = '300px';
            notification.innerHTML = `
                <i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'error' ? 'exclamation-triangle-fill' : 'info-circle-fill'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            `;
            document.body.appendChild(notification);

            // Auto-remove after 4 seconds
            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Initialize luxury features
        document.addEventListener('DOMContentLoaded', function() {
            addLuxuryValidation();
        });
    </script>
    
    @stack('scripts')
</body>
</html>
                                <a class="nav-link {{ request()->routeIs('admin.penyewaans.*') ? 'active' : '' }}" href="{{ route('admin.penyewaans.index') }}">
                                    <i class="bi bi-calendar-check"></i> Kelola Penyewaan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.transaksis.*') ? 'active' : '' }}" href="{{ route('admin.transaksis.index') }}">
                                    <i class="bi bi-credit-card"></i> Kelola Transaksi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                                    <i class="bi bi-bar-chart"></i> Laporan
                                </a>
                            </li>
                        @endif

                        <!-- Owner Menu -->
                        @if(Auth::user()->role->value === 'pemilik')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('owner.motors.*') ? 'active' : '' }}" href="{{ route('owner.motors.index') }}">
                                    <i class="bi bi-motorcycle"></i> Motor Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('owner.penyewaans.*') ? 'active' : '' }}" href="{{ route('owner.penyewaans.index') }}">
                                    <i class="bi bi-calendar-check"></i> Penyewaan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('owner.transaksis.*') ? 'active' : '' }}" href="{{ route('owner.transaksis.index') }}">
                                    <i class="bi bi-credit-card"></i> Transaksi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('owner.reports.*') ? 'active' : '' }}" href="{{ route('owner.reports.index') }}">
                                    <i class="bi bi-bar-chart"></i> Laporan
                                </a>
                            </li>
                        @endif

                        <!-- Penyewa Menu -->
                        @if(Auth::user()->role->value === 'penyewa')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('penyewa.dashboard') ? 'active' : '' }}" href="{{ route('penyewa.dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('penyewa.motors.*') ? 'active' : '' }}" href="{{ route('penyewa.motors.index') }}">
                                    <i class="bi bi-search"></i> Cari Motor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('penyewa.penyewaans.*') ? 'active' : '' }}" href="{{ route('penyewa.penyewaans.index') }}">
                                    <i class="bi bi-calendar-check"></i> Penyewaan Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('penyewa.transaksis.*') ? 'active' : '' }}" href="{{ route('penyewa.transaksis.index') }}">
                                    <i class="bi bi-credit-card"></i> Transaksi Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('penyewa.history') ? 'active' : '' }}" href="{{ route('penyewa.history') }}">
                                    <i class="bi bi-clock-history"></i> Riwayat
                                </a>
                            </li>
                        @endif
                    </ul>

                    <hr class="text-muted">
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle me-2"></i>
                            <strong>{{ Auth::user()->nama }}</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                            <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </a></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
                <!-- Top navbar -->
                <div class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target=".sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="navbar-nav ms-auto">
                            <span class="navbar-text">
                                <i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Page content -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('title', 'Dashboard')</h1>
                    @yield('page-actions')
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>