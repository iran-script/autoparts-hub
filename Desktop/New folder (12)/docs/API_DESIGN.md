# طراحی API

API آینده با `/api/v1` و Sanctum محافظت می‌شود. منابع عمومی کاتالوگ از منابع tenant-scoped جدا هستند. پاسخ‌ها شامل `data`, `meta`, `errors` و correlation id خواهند بود. قرارداد API پیش از انتشار با OpenAPI ثبت می‌شود.
