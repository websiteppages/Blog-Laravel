{{--

    protected $casts
    ------------------
        database data-ஐ automatic-ஆ correct format (array / date / hash) ஆக மாற்றும்
            | Field             | Without casts | With casts    |
            | ----------------- | ------------- | ------------- |
            | email_verified_at | string        | date object   |
            | password          | plain text ❌  | secure hash ✅ |
            | currencies        | JSON string ❌  | PHP array ✅ |

    ------------------------------------------------------------------------





--}}
