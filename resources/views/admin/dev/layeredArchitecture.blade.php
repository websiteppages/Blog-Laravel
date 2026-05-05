{{--

    ஏன் Interface தேவை?
    -------------------------
        HomeController இந்த Interface-ஐ மட்டுமே பார்க்கும். Database மாறினாலும் (MySQL → MongoDB) Controller-ல் ஒரு வரிகூட மாற வேண்டியதில்லை. Concrete class (Implementation) மட்டும் மாறும்.

    ------------------------------------------------------------------------------------
    Enum = fixed values list (change ஆகாத constant values collection)
    ----
        👉 Role values எல்லாம் ஒரே இடத்தில் define பண்ணலாம்
        ✔️ இதனால்: typo error வராது ❌ (admn, adminn)

        ❌ Without Enum - typo error ❌ - duplicate strings ❌ - maintain panna kashtam ❌
    ------------------------------------------------------------------------------------
    Services
    -----------
        public function __construct(
            protected RoleRepositoryInterface $roleRepository,
            protected UserService $userService
        ) {}

            | Situation                     | Use                |
            | ----------------------------- | ------------------ |
            | Business logic / combine data | ✅ `UserService`    |
            | Direct DB fetch மட்டும்       | ✅ `UserRepository` |

            protected PostRepositoryInterface $postRepo;
            --------------------------------------------
                DB-ல இருந்து data மட்டும் எடுக்கணும்

            protected PostService $postService;
            -------------------------------------
                logic + multiple operations + combine data (repo call, filters, calculations, formatting)


                Professional rule
                --------------------
                Service → Repository call பண்ணும்
                Controller → Service call பண்ணும்

                👉 So:

                Service inside → Repository OK
                Service inside → Service OK (careful)


    ------------------------------------------------------------------------------------
    ------------------------------------------------------------------------------------
    ------------------------------------------------------------------------------------













--}}
