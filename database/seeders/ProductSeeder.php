<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brandId = Brand::where('slug', 'bioqem-pharma')->value('id') ?? 1;
        $cat = fn(string $slug) => Category::where('slug', $slug)->value('id');

        $products = [
            // Weight Management
            [
                'category_id'       => $cat('weight-management'),
                'name'              => 'SlimWell Protein Powder',
                'slug'              => 'slimwell-protein-powder',
                'sku'               => 'SLIM-PROT-500G',
                'price'             => 85.14,
                'short_description' => 'Meal-replacement shake for healthy weight management and lean muscle tone.',
                'description'       => "Benefits:\n• Beneficial for weight management\n• Boosts immune system and metabolism\n• Healthy meal replacement shake for lean muscle tone\n• Contains Whey protein for muscle protein synthesis\n• Helps clean and detoxify the body\n\nHow to use: Replace one meal per day with SlimWell Shake. For best results, replace dinner daily.\n\nStorage: Close immediately after use. Consume within 20 days of opening.",
                'stock'             => 50,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['500 gm'],
            ],
            [
                'category_id'       => $cat('weight-management'),
                'name'              => 'SlimQem Capsules',
                'slug'              => 'slimqem-capsules',
                'sku'               => 'SLIMQEM-CAPS-60',
                'price'             => 70.46,
                'short_description' => 'Natural capsules for weight management, metabolism boost and appetite suppression.',
                'description'       => "Benefits:\n1. Weight Management through appetite suppression\n2. Boosts Metabolism\n3. Lean Muscle Maintenance\n4. Detoxification with psyllium husk\n5. Immune Support\n6. Sustained Energy\n\nHow to Use: Take 2 capsules after meals.\n\nKey Ingredients: Whey Protein, Psyllium Husk, Vrikshamla (Garcinia Cambogia), Amalaki, Harada, Katuki.",
                'stock'             => 100,
                'is_featured'       => true,
                'is_trending'       => false,
                'variants'          => ['60 Tabs'],
            ],

            // Bone & Joint Wellness
            [
                'category_id'       => $cat('bone-joint-wellness'),
                'name'              => 'OrthoQem Oil',
                'slug'              => 'orthoqem-oil',
                'sku'               => 'ORTHOQ-OIL-100ML',
                'price'             => 62.52,
                'short_description' => 'Herbal pain relief oil with 20+ Ayurvedic ingredients for joints and muscles.',
                'description'       => "Enriched with 20+ Ayurvedic ingredients, OrthoQem Oil offers effective pain relief without hot/cold burning sensation.\n\nBenefits:\n• Soothes joint and muscle pain\n• Skin-friendly, no irritation\n• Pleasant, relaxing aroma\n\nFor external use only. Avoid contact with eyes and open wounds.",
                'stock'             => 80,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['100ml', '50ml'],
            ],
            [
                'category_id'       => $cat('bone-joint-wellness'),
                'name'              => 'OrthoQem Tablet',
                'slug'              => 'orthoqem-tablet',
                'sku'               => 'ORTHOQ-TAB-60',
                'price'             => 70.69,
                'short_description' => 'Herbo-mineral tablets for arthritis, spondylitis and joint pain.',
                'description'       => "Herbo-mineral combination for arthritic and joint pain management.\n\nBenefits:\n• Effective for spondylitis, backaches, and sprains\n• Reduces swelling of joints and muscles\n• Faster pain relief\n• Strengthens joints and muscles\n\nComposition (per tablet): Mahayograj guggul churna 150mg, Maharasnadi kwata churna 125mg, Nirgundi churna 125mg, Yavani 65mg, Shankha bhasma 65mg, Godanthi bhasma 65mg.\n\nFor best results: minimum three months.",
                'stock'             => 60,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['60 Tabs'],
            ],
            [
                'category_id'       => $cat('bone-joint-wellness'),
                'name'              => 'OrthoQem Gel',
                'slug'              => 'orthoqem-gel',
                'sku'               => 'ORTHOQ-GEL-30G',
                'price'             => 62.70,
                'short_description' => 'Fast-acting herbal gel for muscle and joint pain — non-greasy and skin-friendly.',
                'description'       => "Fast-acting herbal formula for musculoskeletal pain relief.\n\nBenefits:\n• Potent Ayurvedic ingredients target pain and inflammation\n• Non-greasy, no residue\n• Rapid absorption for immediate relief\n• No burning or irritation\n\nUsage: Apply 2-3 times daily. Massage onto affected area until absorbed.",
                'stock'             => 70,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['30gm'],
            ],

            // Respiratory Care
            [
                'category_id'       => $cat('respiratory-care-immunity-booster'),
                'name'              => 'Breath Sure Ayurvedic Cough Kadha',
                'slug'              => 'breath-sure-cough-kadha',
                'sku'               => 'BREATHSURE-KADHA',
                'price'             => 63.12,
                'short_description' => 'Non-drowsy Ayurvedic cough syrup for asthma, bronchitis, COPD, and immunity.',
                'description'       => "Indications: Asthma, Cough, COPD, Bronchitis, Allergies & Sinusitis, Common Cold.\n\nKey Benefits:\n• Supports healthy respiratory function\n• Non-drowsy, non-habit forming\n• Natural expectorant: clears mucus and phlegm\n• Bronchodilator properties\n• Immune boosting\n\nKey Ingredients: Thyme, Ivy (Bimbi), Camphor, Clove, Cardamom, Black Pepper, Ginger, Tulasi, Licorice.\n\nDosage: Adults: 10-15 ml + 20 ml lukewarm water twice daily. Children: 2.5-5 ml.",
                'stock'             => 90,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['100ml', '200ml'],
            ],

            // Kidney Care
            [
                'category_id'       => $cat('kidney-care'),
                'name'              => 'UroQem Syrup',
                'slug'              => 'uroqem-syrup',
                'sku'               => 'UROQEM-SYR-200ML',
                'price'             => 64.17,
                'short_description' => 'Natural urinary alkalizer for UTI, burning micturition and kidney stone support.',
                'description'       => "Benefits:\n• Treats Urinary tract infections\n• Relieves burning micturition\n• Improves renal function\n• Helps with kidney stones\n• Activates complete Urinary system\n• Natural urinary alkalizer\n\nKey Ingredients: Shatavari, Kemua, Ajmod, Nirgundi, Mulethi, Jatamansi, Ashoka, Gokharu, Ashwagandha.",
                'stock'             => 55,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['100ml', '200ml'],
            ],

            // Liver Care
            [
                'category_id'       => $cat('liver-care-detoxification'),
                'name'              => 'LivoQem DS-32',
                'slug'              => 'livoqem-ds-32',
                'sku'               => 'LIVOQEM-DS32-60',
                'price'             => 67.34,
                'short_description' => 'Hepatoprotective tablets for fatty liver, hepatitis, jaundice and liver detoxification.',
                'description'       => "Natural solution for liver health and detoxification.\n\nKey Benefits: Liver detoxification and repair, fatty liver and hepatitis support, reduces toxins, supports digestion, helps with jaundice and anemia.\n\nKey Ingredients (per tablet): Bhumi Amla 100mg, Bhringraj 75mg, Kutki 75mg, Giloy 50mg, Kalmegh 50mg, Makoy 50mg, Punarnava 50mg, Arjuna 25mg, Daru Haldi 25mg.\n\nDirections: 2 tablets morning and evening before meals.",
                'stock'             => 65,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['60 Tablets'],
            ],
            [
                'category_id'       => $cat('liver-care-detoxification'),
                'name'              => 'Live Amrit DS Syrup',
                'slug'              => 'live-amrit-ds-syrup',
                'sku'               => 'LIVEAMRIT-DS-200ML',
                'price'             => 63.88,
                'short_description' => 'Polyherbal liver tonic for fatty liver, hepatitis, jaundice and liver detoxification.',
                'description'       => "Comprehensive liver health syrup.\n\nKey Benefits: Improves digestion, restores liver function, accelerates liver cell regeneration, protects from alcohol damage, supports convalescence.\n\nUseful in: NAFLD, Hepatitis, Jaundice, Liver Dysfunction, Cirrhosis.\n\nKey Ingredients: Milk Thistle, Kalmegh, Kutki, Punarnava, Bhringraj, Bhuiamla, Amla, Giloy, Makoi, Daruhaldi, Aloe vera.\n\nDosage: Children: 1/2 tsp twice daily. Adults: 2-3 tsp, 3-4 times daily.",
                'stock'             => 75,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['200ml'],
            ],

            // Heart Care
            [
                'category_id'       => $cat('heart-care'),
                'name'              => 'CardioPro Syrup',
                'slug'              => 'cardiopro-syrup',
                'sku'               => 'CARDIOPRO-SYR-500ML',
                'price'             => 71.51,
                'short_description' => '100% natural, sugar-free cardiac tonic for cholesterol, blood pressure and heart health.',
                'description'       => "Key Ingredients: Hawthorn, Grape Seed Extract, Kesar, Arjuna, Amla, Harad, Shatavari, Ashwagandha, Kaali Mirch, Jeera, Adrak, Pudina.\n\nKey Benefits: 100% natural and sugar-free, cardiac tonic, fights bad cholesterol (LDL), anti-inflammatory, supports blood circulation, prevents congestive heart failure. Helpful in Hypertension, Dyslipidemia, Coronary Heart Disease.\n\nDirections: 20-30 ml twice daily. Recommended treatment: 4-12 weeks.",
                'stock'             => 40,
                'is_featured'       => true,
                'is_trending'       => false,
                'variants'          => ['500ml'],
            ],

            // Diabetic Care
            [
                'category_id'       => $cat('diabetic-care'),
                'name'              => 'DiboWell Protein Powder',
                'slug'              => 'dibowell-protein-powder',
                'sku'               => 'DIBOWELL-PROT-200G',
                'price'             => 69.41,
                'short_description' => 'Complete nutrition for diabetics — supports blood sugar management and muscle health.',
                'description'       => "Complete nutrition for diabetics.\n\nAnti-Diabetic Blend: Banaba Extract, Guggle, Bitter Melon, Licorice, Cinnamon, Gurmar, Bilberry, Fenugreek.\n\nNutritional Blend: Whey Protein, Soya Oil, Maltodextrin, Dietary Fiber, DHA, Alpha Lipoic Acid, L-Carnitine, L-Taurine.\n\nKey Benefits: Healthy blood sugar, liver and kidney support, heart health, reduces cravings, prevents muscle loss.\n\nHow to Use: Add 10 gm (1 scoop) to 50 ml water. Use within 1 month of opening.",
                'stock'             => 45,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['200 Gm'],
            ],
            [
                'category_id'       => $cat('diabetic-care'),
                'name'              => 'DiboQem Tablet',
                'slug'              => 'diboqem-tablet',
                'sku'               => 'DIBOQEM-TAB-60',
                'price'             => 75.09,
                'short_description' => 'Clinically proven herbal tablets to regulate glucose and improve insulin sensitivity.',
                'description'       => "Natural formulation for healthy glucose metabolism.\n\nKey Benefits: Regulates glucose metabolism, improves insulin sensitivity, boosts glycogen storage, balances cholesterol, protects liver, rejuvenates pancreatic B cells.\n\nKey Ingredients (per tablet): Swarnapatri 150mg, Madhunashini (Gymnema) 120mg, Saptachakra 120mg, Jambu 80mg, Karavallaka (Bitter Melon) 80mg, Haridra 80mg.\n\nHow to Use: 2 tablets twice daily after meals.",
                'stock'             => 60,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['60 Tabs'],
            ],

            // Digestive Health
            [
                'category_id'       => $cat('digestive-health-enhancer'),
                'name'              => 'LaxoQem Powder',
                'slug'              => 'laxoqem-powder',
                'sku'               => 'LAXOQEM-PWD-75G',
                'price'             => 63.77,
                'short_description' => 'Ayurvedic constipation powder to regulate bowel movement and improve digestion.',
                'description'       => "100% natural constipation and indigestion remedy.\n\nBenefits: Relief from constipation, maintains healthy digestive system, stimulates secretion of digestive juices.\n\nIngredients: Isabgol, Sonamukhi, Harad, Amaltas, Mulethi, Saunf, Kala Namak.\n\nHow to Use: 1-2 teaspoons with lukewarm water before bedtime.",
                'stock'             => 85,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['75 gm'],
            ],
            [
                'category_id'       => $cat('digestive-health-enhancer'),
                'name'              => 'Digo-Amrit Syrup',
                'slug'              => 'digo-amrit-syrup',
                'sku'               => 'DIGOAMRIT-SYR-200ML',
                'price'             => 63.88,
                'short_description' => 'Natural digestive syrup for indigestion, acidity, gas, bloating, GERD and constipation.',
                'description'       => "Natural solution for digestive complaints.\n\nKey Benefits: Activates digestive system, neutralizes gastric acid, relieves gas and flatulence, alleviates heartburn and GERD, improves enzyme secretion, stimulates appetite.\n\nKey Ingredients: Papaya, Guava, Triphala, Bael Fruit, Dalchini, Pudina, Ginger, Black Pepper, Pippali, Jeera, Haritaki.\n\nDosage: Children: 1/2 tsp twice daily. Adults: 2-3 tsp, 3-4 times daily.",
                'stock'             => 70,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['100ml', '200ml'],
            ],

            // Women's Health
            [
                'category_id'       => $cat('womens-health-enhancer'),
                'name'              => 'GyNo-Cyst Tablets',
                'slug'              => 'gyno-cyst-tablets',
                'sku'               => 'GYNOCYST-TAB-30',
                'price'             => 73.61,
                'short_description' => 'Non-hormonal dietary supplement for PCOS/PCOD management and hormonal balance.',
                'description'       => "Non-hormonal PCOS/PCOD management supplement.\n\nBenefits: Natural solution for ovulation and implantation, enhances insulin response, maintains hormonal balance, supports female reproductive health.\n\nKey Ingredients (per tablet): Myo-inositol 1000mg, Fenumannan® (Fenugreek Extract 60% Galactomannan) 100mg, L-methylfolate Calcium 100mcg, Vitamin D3 1000 IU.\n\nDirection of Use: One capsule daily.",
                'stock'             => 50,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['30 Tablets'],
            ],
            [
                'category_id'       => $cat('womens-health-enhancer'),
                'name'              => 'GynoSure Syrup',
                'slug'              => 'gynosure-syrup',
                'sku'               => 'GYNOSURE-SYR',
                'price'             => 64.40,
                'short_description' => 'Polyherbal non-hormonal syrup for menstrual irregularities, PCOS, and female infertility.',
                'description'       => "Polyherbal, non-hormonal Ayurvedic remedy for female health.\n\nBenefits: Enhances endometrial health, normalizes menstrual blood flow, supports oligomenorrhea, DUB, metrorrhagia treatment, helps with PCOS, leucorrhea, uterine spasms.\n\nDirection of Use: 15-20 ml with water twice daily, an hour after meals. Minimum 3 months. Discontinue during menstruation.",
                'stock'             => 55,
                'is_featured'       => true,
                'is_trending'       => false,
                'variants'          => ['200ml', '470ml'],
            ],

            // Men's Health
            [
                'category_id'       => $cat('mens-health-enhancer'),
                'name'              => 'Shilajit Gold Resin',
                'slug'              => 'shilajit-gold-resin',
                'sku'               => 'SHILAJIT-GOLD-RESIN',
                'price'             => 95.62,
                'short_description' => '100% pure Himalayan Shilajit resin with Ashwagandha and Swarna Bhasma for strength and vitality.',
                'description'       => "100% Pure Himalayan Shilajit with Swarna Bhasma.\n\nHealth Benefits: Boosts immunity, enhances strength and stamina, slows aging, reduces stress, boosts libido, strengthens bones and muscles.\n\nKey Ingredients (per 1g): Shilajit 350mg, Ashwagandha Extract 150mg, Coffee Extract 150mg, Gokshura Extract 100mg, Haldi Extract 50mg, Kali Musli Extract 50mg, Elaichi Oil 10mg, Swarna Bhasma 0.50mg.\n\nFeatures: High Fulvic Acid, 80+ Micronutrients, Clinically Tested for Heavy Metals.",
                'stock'             => 30,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['20 gm'],
            ],

            [
                'category_id'       => $cat('mens-health-enhancer'),
                'name'              => 'Virya Vardhana Powder',
                'slug'              => 'virya-vardhana-powder',
                'sku'               => 'VIRYA-VARDH-100G',
                'price'             => 68.36,
                'short_description' => 'Ayurvedic stamina booster and muscle mass builder for male vitality and sperm health.',
                'description'       => "Bioqem's Virya Vardhana Powder — ultimate Stamina Booster and muscle mass builder, blending Ayurvedic and Unani herbs with potent ingredients like Shukral and Vajikarak.\n\nBenefits:\n• Enhances male wellness and sperm health\n• Energizes and keeps you active\n• Improves stamina, vigour, and vitality\n• Aids in stress and anxiety management\n\nDosage: Take 1 teaspoon twice daily with milk or lukewarm water, after meals, or as directed by your physician.",
                'stock'             => 50,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['100 gm'],
            ],

            [
                'category_id'       => $cat('mens-health-enhancer'),
                'name'              => 'Bionile Advance Penile Oil',
                'slug'              => 'bionile-advance-penile-oil',
                'sku'               => 'BIONILE-OIL',
                'price'             => 64.72,
                'short_description' => 'Advanced Penile Care Formula Enriched with Sensual alluring Ylang Ylang.',
                'description'       => "Bioqem Pharma Bionile Penile Oil — herbal topical treatment for erectile dysfunction (ED), premature ejaculation, and penile size concerns. 100% safe herbal oil.\n\nKey Benefits:\n• Tejpatta and Jyotishmati act as nervine stimulants, stimulate blood vessels, causing vasodilation and increased blood flow\n• Almonds act as a nervine tonic, relaxing smooth muscles\n• Nirgundi exerts soothing, analgesic and anti-inflammatory effects\n• Contains Latha Kasthuri and other aphrodisiac ingredients for ED treatment\n• Local antioxidant action prevents oxidative damage\n\nKey Ingredients: Jyotishmati, Latha Kasthuri, Vatada (Almonds), Nirgundi, Karpasa, Mukulaka, Jaiphal, Mace (Javitri), Clove, Indian Bay Leaf (Tejpatta), Dhaturra, Kesar, Ashwagandha, Gunja, Shatavari.\n\nDirection to Use: Apply a small quantity onto palm. Gently massage onto penis (avoiding the glans) and pubic area. Rub gently for 30-60 seconds and allow to absorb.",
                'stock'             => 60,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['15 ml', '30 ml'],
            ],

            [
                'category_id'       => $cat('mens-health-enhancer'),
                'name'              => 'Bionile Insta Gel',
                'slug'              => 'bionile-insta-gel',
                'sku'               => 'BIONILE-GEL-5G',
                'price'             => 63.14,
                'short_description' => 'Herbal gel for topical treatment of erectile dysfunction, premature ejaculation and penile concerns.',
                'description'       => "Bioqem Pharma Bionile Insta Gel — herbal proprietary medicine for topical treatment of ED, premature ejaculation and thin penis. Enhances vasodilation, has moisturizing and soothing effects.\n\nKey Ingredients: Jyotishmati, Latha Kasthuri, Vatada (Almonds), Nirgundi, Karpasa, Mukulaka, Jaiphal, Mace (Javitri), Clove (Laung), Indian Bay Leaf (Tejpatta), Dhaturra, Kesar, Alasi, Jaitun, Tila, Ashwagandha Root Extract, Gunja Root Extract, Ashwatha (Sacred Fig), Akarkara Root Extract, Shatavari Root Extract.\n\nKey Benefits:\n• Increases blood flow to penile tissue for stronger erection\n• Relaxes smooth muscles via vasodilation\n• Soothing, analgesic and anti-inflammatory effects\n• Local antioxidant action prevents oxidative damage\n\nDirections: Put a small quantity on palm. Apply on penis (except glans) and pubic area. Gently rub for 30-60 seconds and let absorb.\n\nSafety: Read label before use. Keep out of reach of children. Store in a cool dry place.",
                'stock'             => 60,
                'is_featured'       => false,
                'is_trending'       => false,
                'variants'          => ['5 gm'],
            ],

            [
                'category_id'       => $cat('mens-health-enhancer'),
                'name'              => "Men's Drive",
                'slug'              => 'mens-drive',
                'sku'               => 'MENS-DRIVE-CAPS',
                'price'             => 69.01,
                'short_description' => 'Clinically proven herbal capsules with 18 powerful herbs for male strength, stamina and vitality.',
                'description'       => "Bioqem Pharma Men's Drive Veg Capsule — herbal supplement with clinically proven standardized herbal extracts for men's reproductive health and vitality.\n\nBenefits:\n• Maintains men's reproductive health\n• Increases strength and energy, boosts overall vitality\n• Enhances vigor and vitality\n• Antioxidants protect against free radicals from junk food, alcohol, smoking\n• Reduces cholesterol absorption and regulates lipid levels\n• Increases blood vessel elasticity and protects heart from stress\n• Contains 18 powerful herbs and minerals as revitalizers\n• Regulates circulation and manages anxiety and stress\n\nIngredients: Panax Ginseng, Ashwagandha, Ginkgo Biloba, Kaunch Beej, Shilajit, Tribulus Terrestris, Fenugreek Seed Extract, Piper Nigrum, Horny Goat Weed Extract, Giloy Extract, Javitri, L-Arginine, Coenzyme Q10, Tongkat Ali Powder, Maca Root Powder, Caffeine Anhydrous, Yohimbine, Saw Palmetto, Saffron, Zinc, Selenium, Vitamin B3.",
                'stock'             => 80,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['10 Caps', '30 Caps', '100 Caps', '1000 Caps'],
            ],

            [
                'category_id'       => $cat('mens-health-enhancer'),
                'name'              => 'Happy Knights Prash',
                'slug'              => 'happy-knights-prash',
                'sku'               => 'HAPPY-KNIGHTS-PRASH',
                'price'             => 73.61,
                'short_description' => 'Premium herbal prash for erectile dysfunction, premature ejaculation, low libido and male stamina.',
                'description'       => "Bioqem Pharma Happy Knights for Men — addresses erectile dysfunction (ED), premature ejaculation, low libido, and low stamina using a blend of natural ingredients.\n\nKey Benefits:\n1. Improves libido and overall performance\n2. Supports healthy blood circulation for erectile function\n3. Enhances satisfaction and sexual experience\n4. Boosts vigor, vitality and reduces fatigue\n5. Increases stamina and endurance\n6. Strengthens nerves and combats general debility\n7. May help treat erectile dysfunction (ED)\n8. Controls premature ejaculation\n9. Increases semen density and sperm count\n\nIndications: Erectile Dysfunction, Premature Ejaculation, Oligospermia, Loss of Libido, Infertility, Immunity Booster.\n\nKey Ingredients: Korean Red Ginseng, Ashwagandha, Royal Jelly, Safed Musali, Siyah Musali, Gokhru (Tribulus terrestris), Vidarikand, Satawar, Giloy, Shilajit, Zafran (Saffron), Ginger, Kamarkas, Kaunch Beej, Elaichi.\n\nHow to Use: Take 1 teaspoon (5 gm) before or after 2 hours of food. Chew with cold milk as directed. For best results, use with Men's Drive capsules and Bionile Oil/Gel.\n\nCaution: Keep out of reach of children. Store in a cool, dry place.",
                'stock'             => 40,
                'is_featured'       => true,
                'is_trending'       => true,
                'variants'          => ['125 gm', '250 gm', '500 gm', '1 kg'],
            ],
        ];

        foreach ($products as $data) {
            $variantNames = $data['variants'] ?? [];
            unset($data['variants']);

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'brand_id'  => $brandId,
                    'rating'    => 4.5,
                    'is_active' => true,
                ])
            );

            foreach ($variantNames as $index => $variantName) {
                ProductVariant::updateOrCreate(
                    ['product_id' => $product->id, 'name' => $variantName],
                    [
                        'sku'        => null,
                        'price'      => $data['price'],
                        'stock'      => intval($data['stock'] / max(count($variantNames), 1)),
                        'is_default' => $index === 0,
                        'is_active'  => true,
                        'sort_order' => $index,
                    ]
                );
            }
        }
    }
}
