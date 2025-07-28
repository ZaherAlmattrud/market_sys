<template>
    <div class="container">
        <!-- شريط البحث -->
        <div class="search-row">
            <input v-model="search" @input="fetchAudits()" placeholder="🔍 ابحث عن حدث، رابط أو مستخدم..."
                class="search-input" />
        </div>

        <!-- جدول السجل -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الحدث</th>
                    <th>الكيان</th>
                    <th>المستخدم</th>
                    <th>البيانات القديمة</th>
                    <th>البيانات الحديثة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="audit in audits.data" :key="audit.id">
                    <td>{{ audit.id }}</td>

                    <td>{{ audit.event }}</td>
                    <td>{{ audit.auditable_type }}</td>
                    <td>{{ audit.user?.user_name ?? 'غير معروف' }}</td>



                    <td>{{ audit.old_values }}</td>
                    <td>{{ audit.new_values }}</td>


                    <td>{{ formatDate(audit.created_at) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- الباجنيشن -->
        <div v-if="audits.links && audits.links.length" class="pagination">
            <button v-for="(link, index) in audits.links" :key="index" v-html="link.label" :disabled="!link.url"
                :class="{ active: link.active }" @click="goToPage(link.url)"></button>
        </div>
    </div>
</template>

<script>
export default {
    name: "AuditLogPage",
    data() {
        return {
            search: "",
            audits: { data: [], links: [], current_page: 1, last_page: 1 },
        };
    },
    mounted() {
        this.fetchAudits();
    },
    methods: {
        fetchAudits(page = 1) {
            axios
                .get(`/api/audits?page=${page}&search=${this.search}`)
                .then((res) => {
                    this.audits = res.data;
                })
                .catch((err) => console.error("فشل تحميل السجلات", err));
        },
        goToPage(url) {
            const page = new URL(url).searchParams.get("page");
            this.fetchAudits(page);
        },
        formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleString("ar-EG");
        },
    },
};
</script>

<style scoped>
.container {
    max-width: 900px;
    margin: 50px auto;
    font-family: 'Roboto', sans-serif;
    padding: 0 15px;
    direction: rtl;
    background: #ffffff;
}

.search-row {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.search-input {
    flex: 1;
    padding: 10px 12px;
    font-size: 14px;
    border: 1px solid #a3c1f7;
    border-radius: 4px;
    outline: none;
    background-color: #f0f5ff;
    color: #1a237e;
}

.search-input:focus {
    border-color: #1976d2;
    background-color: #e6f0ff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(92, 157, 237, 0.15);
    border-radius: 8px;
    overflow: hidden;
    background: #f9fbff;
}

thead {
    background-color: #d6e4ff;
    color: #0d47a1;
    font-weight: 600;
}

table th,
table td {
    padding: 12px 15px;
    text-align: center;
    border-bottom: 1px solid #b3c7ff;
    color: #1a237e;
}

tbody tr:hover {
    background-color: #e6f0ff;
    cursor: pointer;
}

.pagination {
    margin-top: 20px;
    text-align: center;
}

.pagination button {
    min-width: 36px;
    margin: 0 3px;
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 4px;
    background-color: #dae6ff;
    color: #0d47a1;
    border: 1px solid transparent;
    transition: all 0.3s ease;
}

.pagination button:hover:not(:disabled) {
    background-color: #1976d2;
    color: white;
    border-color: #1976d2;
}

.pagination button.active {
    background-color: #1976d2;
    color: white;
    font-weight: 600;
    border-color: #115293;
}

.pagination button:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}
</style>
