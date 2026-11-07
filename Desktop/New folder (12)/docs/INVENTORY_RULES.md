# قواعد انبار

دفتر کل StockMovement append-only است. StockBalance با تراکنش PostgreSQL و `SELECT ... FOR UPDATE` تغییر می‌کند. available = physical - valid reservations. عملیات idempotency key خواهد داشت و فروش بیش از موجودی طبق سیاست فروشگاه رد می‌شود.
