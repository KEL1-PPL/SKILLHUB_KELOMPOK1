@extends('layouts.app')

@push('styles')
<style>
    .home-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    /* Hero Section */
    .hero-section {
        text-align: center;
        padding: 4rem 1rem;
        background: linear-gradient(135deg, var(--pastel-blue), var(--pastel-orange));
        border-radius: 20px;
        margin-bottom: 3rem;
    }

    .hero-section h1 {
        font-size: 2.5rem;
        color: var(--text-dark);
        margin-bottom: 1.5rem;
        font-weight: 700;
    }

    .hero-section p {
        font-size: 1.2rem;
        color: var(--text-gray);
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    /* Search Bar */
    .search-bar {
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        gap: 1rem;
    }

    .search-input {
        flex: 1;
        padding: 1rem;
        border: none;
        border-radius: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .search-button {
        padding: 1rem 2rem;
        background-color: var(--deep-pastel-blue);
        color: var(--pure-white);
        border: none;
        border-radius: 30px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .search-button:hover {
        background-color: var(--deep-pastel-orange);
    }

    /* Categories Section */
    .categories-section {
        margin: 3rem 0;
    }

    .section-title {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        color: var(--text-dark);
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }

    .category-card {
        background: var(--pure-white);
        padding: 1.5rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-5px);
    }

    /* Featured Products */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    .product-card {
        background: var(--pure-white);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: var(--light-pastel-blue);
    }

    .product-info {
        padding: 1rem;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .product-price {
        font-size: 1.2rem;
        color: var(--deep-pastel-blue);
        font-weight: 700;
    }

    .product-location {
        font-size: 0.9rem;
        color: var(--text-gray);
        margin-top: 0.5rem;
    }

    .condition-tag {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        background-color: var(--light-pastel-orange);
        color: var(--text-dark);
        border-radius: 15px;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    @media (max-width: 768px) {
        .hero-section {
            padding: 2rem 1rem;
        }

        .hero-section h1 {
            font-size: 2rem;
        }

        .search-bar {
            flex-direction: column;
        }

        .search-button {
            width: 100%;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<div class="home-container">
    <section class="hero-section">
        <h1>Find Quality Secondhand Items on Loka.<span style="color: var(--deep-pastel-blue)">Hub</span></h1>
        <p>Your local marketplace for pre-loved treasures. Buy and sell sustainably.</p>
        <div class="search-bar">
            <input type="text" class="search-input" placeholder="What are you looking for?">
            <button class="search-button">Search</button>
        </div>
    </section>

    <section class="categories-section">
        <h2 class="section-title">Browse Categories</h2>
        <div class="categories-grid">
            <div class="category-card">
                <h3>Electronics</h3>
            </div>
            <div class="category-card">
                <h3>Furniture</h3>
            </div>
            <div class="category-card">
                <h3>Fashion</h3>
            </div>
            <div class="category-card">
                <h3>Books</h3>
            </div>
            <div class="category-card">
                <h3>Sports</h3>
            </div>
            <div class="category-card">
                <h3>Collectibles</h3>
            </div>
        </div>
    </section>

    <section class="featured-section">
        <h2 class="section-title">Featured Items</h2>
        <div class="products-grid">
            <!-- Sample Product Cards -->
            <div class="product-card">
                <img src="/api/placeholder/400/320" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Vintage Leather Bag</h3>
                    <p class="product-price">Rp 450.000</p>
                    <p class="product-location">📍 Jakarta Selatan</p>
                    <span class="condition-tag">Like New</span>
                </div>
            </div>
            <div class="product-card">
                <img src="/api/placeholder/400/320" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">iPhone 11 Pro 256GB</h3>
                    <p class="product-price">Rp 5.999.000</p>
                    <p class="product-location">📍 Bandung</p>
                    <span class="condition-tag">Good Condition</span>
                </div>
            </div>
            <div class="product-card">
                <img src="/api/placeholder/400/320" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Wooden Coffee Table</h3>
                    <p class="product-price">Rp 750.000</p>
                    <p class="product-location">📍 Surabaya</p>
                    <span class="condition-tag">Lightly Used</span>
                </div>
            </div>
            <div class="product-card">
                <img src="/api/placeholder/400/320" alt="Product" class="product-image">
                <div class="product-info">
                    <h3 class="product-title">Nintendo Switch</h3>
                    <p class="product-price">Rp 3.200.000</p>
                    <p class="product-location">📍 Yogyakarta</p>
                    <span class="condition-tag">Excellent</span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to product cards on scroll
        const cards = document.querySelectorAll('.product-card');

        const observerOptions = {
            threshold: 0.2
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(card);
        });
    });
</script>
@endpush
