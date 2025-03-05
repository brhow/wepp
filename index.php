<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات في Firestore</title>
    <style>
        /* تحسينات عامة */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2 {
            color: #007BFF;
            margin-bottom: 20px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        img {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            transition: transform 0.2s;
        }

        img:hover {
            transform: scale(1.1);
        }

        button {
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .edit-btn {
            background-color: #FFC107;
            color: white;
        }

        .edit-btn:hover {
            background-color: #e0a800;
        }

        .delete-btn {
            background-color: #DC3545;
            color: white;
            margin-left: 5px;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }

        /* تصميم متجاوب */
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                display: none;
            }

            tr {
                margin-bottom: 15px;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
                border-bottom: 1px solid #ddd;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                text-align: left;
                font-weight: bold;
                color: #007BFF;
            }

            img {
                width: 40px;
                height: 40px;
            }

            button {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>

    <!-- استيراد Firebase -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/11.3.1/firebase-app.js";
        import { getFirestore, collection, getDocs, doc, updateDoc, deleteDoc } from "https://www.gstatic.com/firebasejs/11.3.1/firebase-firestore.js";

        // 🔥 بيانات Firebase الخاصة بك
        const firebaseConfig = {
            apiKey: "AIzaSyBux-YTCJ0fxZqab_TdYod9tbhJ85tFf3I",
            authDomain: "brhk-10704.firebaseapp.com",
            projectId: "brhk-10704",
            storageBucket: "brhk-10704.appspot.com",
            messagingSenderId: "1007698165292",
            appId: "1:1007698165292:web:ed54e70da57974acc51b89"
        };

        // تهيئة Firebase
        const app = initializeApp(firebaseConfig);
        const db = getFirestore(app);

        // 🟢 جلب المنتجات من Firestore وعرضها في الجدول
        async function fetchProducts() {
            const productsRef = collection(db, "products");
            const querySnapshot = await getDocs(productsRef);
            let tableContent = '';
            querySnapshot.forEach((doc) => {
                const product = doc.data();
                tableContent += `
                    <tr id="row-${doc.id}">
                        <td data-label="المعرف">${doc.id}</td>
                        <td data-label="الاسم"><span id="name-${doc.id}">${product.name}</span></td>
                        <td data-label="الوصف"><span id="desc-${doc.id}">${product.description}</span></td>
                        <td data-label="السعر"><span id="price-${doc.id}">${product.price}</span></td>
                        <td data-label="الصورة"><img src="${product.image}" alt="صورة المنتج" /></td>
                        <td data-label="الإجراء">
                            <button class="edit-btn" onclick="editProduct('${doc.id}')">✏️ تعديل</button>
                            <button class="delete-btn" onclick="deleteProduct('${doc.id}')">🗑️ حذف</button>
                            <button class="save-btn" onclick="saveProduct('${doc.id}')" style="display:none;">💾 حفظ</button>
                        </td>
                    </tr>
                `;
            });
            document.getElementById('productTable').innerHTML = tableContent;
        }

        // ✏️ تفعيل التعديل على الحقول
        window.editProduct = function(productId) {
            document.querySelector(`#name-${productId}`).outerHTML = `<input type="text" id="edit-name-${productId}" value="${document.getElementById(`name-${productId}`).innerText}">`;
            document.querySelector(`#desc-${productId}`).outerHTML = `<input type="text" id="edit-desc-${productId}" value="${document.getElementById(`desc-${productId}`).innerText}">`;
            document.querySelector(`#price-${productId}`).outerHTML = `<input type="text" id="edit-price-${productId}" value="${document.getElementById(`price-${productId}`).innerText}">`;

            document.querySelector(`#row-${productId} .edit-btn`).style.display = "none";  // إخفاء زر التعديل
            document.querySelector(`#row-${productId} .delete-btn`).style.display = "none";  // إخفاء زر الحذف
            document.querySelector(`#row-${productId} .save-btn`).style.display = "inline-block";  // إظهار زر الحفظ
        };

        // 💾 حفظ التعديلات في Firestore
        window.saveProduct = async function(productId) {
            const newName = document.getElementById(`edit-name-${productId}`).value;
            const newDesc = document.getElementById(`edit-desc-${productId}`).value;
            const newPrice = document.getElementById(`edit-price-${productId}`).value;

            const productRef = doc(db, "products", productId);
            await updateDoc(productRef, {
                name: newName,
                description: newDesc,
                price: newPrice
            }).then(() => {
                alert("✅ تم تحديث المنتج بنجاح!");
                document.querySelector(`#edit-name-${productId}`).outerHTML = `<span id="name-${productId}">${newName}</span>`;
                document.querySelector(`#edit-desc-${productId}`).outerHTML = `<span id="desc-${productId}">${newDesc}</span>`;
                document.querySelector(`#edit-price-${productId}`).outerHTML = `<span id="price-${productId}">${newPrice}</span>`;

                document.querySelector(`#row-${productId} .edit-btn`).style.display = "inline-block";  // إظهار زر التعديل
                document.querySelector(`#row-${productId} .delete-btn`).style.display = "inline-block";  // إظهار زر الحذف
                document.querySelector(`#row-${productId} .save-btn`).style.display = "none";  // إخفاء زر الحفظ
            }).catch((error) => {
                console.error("⚠️ حدث خطأ أثناء التحديث: ", error);
            });
        };

        // 🗑️ حذف منتج
        window.deleteProduct = async function(productId) {
            if (confirm("⚠️ هل أنت متأكد من حذف هذا المنتج؟")) {
                const productRef = doc(db, "products", productId);
                await deleteDoc(productRef).then(() => {
                    alert("✅ تم حذف المنتج بنجاح!");
                    fetchProducts();
                }).catch((error) => {
                    console.error("⚠️ حدث خطأ أثناء الحذف: ", error);
                });
            }
        };

        // ⏳ تحميل المنتجات عند فتح الصفحة
        window.onload = fetchProducts;
    </script>
</head>
<body>
    <h2>📦 قائمة المنتجات</h2>
    <table>
        <thead>
            <tr>
                <th>المعرف</th>
                <th>الاسم</th>
                <th>الوصف</th>
                <th>السعر</th>
                <th>الصورة</th>
                <th>الإجراء</th>
            </tr>
        </thead>
        <tbody id="productTable">
            <!-- سيتم إدراج المنتجات هنا -->
        </tbody>
    </table>
</body>
</html>