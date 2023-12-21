SELECT DATE_FORMAT(balance_date, "%b %Y") as month, AVG(balance) as balance
FROM Daily_Account_Balance
GROUP BY month
ORDER BY month ASC;
