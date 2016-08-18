{% if article.full == true %}
<div class="article">
    <h2 class="article-title">{{ article.title }}</h2>
    <div class="article-time">{{ article.time }}</div>
    <div class="article-text">{{ article.text }}</div>
</div>
{% else %}
<div class="articles">
    <h2 class="articles-title">{{ article.articles_title }}</h2>
    <div class="articles-list">
        {% for article in article.articles_list %}
        <div class="article-short">
            <h3 class="article-title-short">{{ article.title }}</h3>
            <div class="article-time-short">{{ article.time }}</div>
            <div class="article-text-short">{{ article.text }}</div>
            <a href="{{ article.link }}" class="article-link">--></a>
        </div>
        {% endfor %}
    </div>
    <div class="articles-pages">
        {% for page in article.articles_pages_links %}
        <a href="{{ page.link }}">{{ page.title }}</a>
        {% endfor %}
    </div>
</div>
{% endif %}