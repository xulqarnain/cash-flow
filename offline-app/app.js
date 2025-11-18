/**
 * Cash Record - Offline Alpine.js App
 * Complete data management with IndexedDB
 */

// Main Alpine.js Component
function cashApp() {
    return {
        // State
        currentView: 'dashboard',
        selectedMonth: 'January',
        showPersonForm: false,
        showSuccess: false,
        successMessage: '',

        // Form Data
        personForm: {
            id: null,
            name: '',
            type: 'give',
            amount: '',
            date: new Date().toISOString().split('T')[0]
        },

        // Data
        people: [],
        transactions: [],

        // Database
        db: null,

        // Initialize
        async init() {
            console.log('Initializing Cash Record App...');
            await this.initDatabase();
            await this.loadData();
            console.log('App initialized successfully');
        },

        // Database Initialization
        async initDatabase() {
            return new Promise((resolve, reject) => {
                const request = indexedDB.open('CashRecordDB', 1);

                request.onerror = () => {
                    console.error('Database error:', request.error);
                    reject(request.error);
                };

                request.onsuccess = () => {
                    this.db = request.result;
                    console.log('Database opened successfully');
                    resolve();
                };

                request.onupgradeneeded = (event) => {
                    const db = event.target.result;

                    // Create people store
                    if (!db.objectStoreNames.contains('people')) {
                        const peopleStore = db.createObjectStore('people', { keyPath: 'id', autoIncrement: true });
                        peopleStore.createIndex('name', 'name', { unique: false });
                        peopleStore.createIndex('created_at', 'created_at', { unique: false });
                    }

                    // Create transactions store
                    if (!db.objectStoreNames.contains('transactions')) {
                        const txStore = db.createObjectStore('transactions', { keyPath: 'id', autoIncrement: true });
                        txStore.createIndex('person_id', 'person_id', { unique: false });
                        txStore.createIndex('date', 'date', { unique: false });
                        txStore.createIndex('type', 'type', { unique: false });
                    }

                    console.log('Database schema created');
                };
            });
        },

        // Load all data
        async loadData() {
            await this.loadPeople();
            await this.loadTransactions();
        },

        // CRUD - People
        async loadPeople() {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['people'], 'readonly');
                const store = tx.objectStore('people');
                const request = store.getAll();

                request.onsuccess = () => {
                    this.people = request.result.sort((a, b) => b.id - a.id);
                    console.log('Loaded people:', this.people.length);
                    resolve();
                };

                request.onerror = () => reject(request.error);
            });
        },

        async addPerson(person) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['people'], 'readwrite');
                const store = tx.objectStore('people');
                const request = store.add(person);

                request.onsuccess = () => {
                    console.log('Person added with ID:', request.result);
                    resolve(request.result);
                };

                request.onerror = () => reject(request.error);
            });
        },

        async updatePerson(id, person) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['people'], 'readwrite');
                const store = tx.objectStore('people');
                person.id = id;
                const request = store.put(person);

                request.onsuccess = () => resolve();
                request.onerror = () => reject(request.error);
            });
        },

        async deletePerson(id) {
            if (!confirm('Delete this person and all their transactions?')) return;

            if (!this.db) return;

            try {
                // Delete all transactions for this person
                await this.deleteTransactionsByPerson(id);

                // Delete the person
                await new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['people'], 'readwrite');
                    const store = tx.objectStore('people');
                    const request = store.delete(id);

                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });

                await this.loadData();
                this.showSuccessMessage('Person deleted successfully!');
            } catch (error) {
                console.error('Error deleting person:', error);
                alert('Failed to delete person');
            }
        },

        // CRUD - Transactions
        async loadTransactions() {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readonly');
                const store = tx.objectStore('transactions');
                const request = store.getAll();

                request.onsuccess = () => {
                    this.transactions = request.result.sort((a, b) => {
                        return new Date(b.date) - new Date(a.date);
                    });
                    console.log('Loaded transactions:', this.transactions.length);
                    resolve();
                };

                request.onerror = () => reject(request.error);
            });
        },

        async addTransaction(transaction) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readwrite');
                const store = tx.objectStore('transactions');
                const request = store.add(transaction);

                request.onsuccess = () => {
                    console.log('Transaction added with ID:', request.result);
                    resolve(request.result);
                };

                request.onerror = () => reject(request.error);
            });
        },

        async deleteTransaction(id) {
            if (!confirm('Delete this transaction?')) return;

            if (!this.db) return;

            try {
                await new Promise((resolve, reject) => {
                    const tx = this.db.transaction(['transactions'], 'readwrite');
                    const store = tx.objectStore('transactions');
                    const request = store.delete(id);

                    request.onsuccess = () => resolve();
                    request.onerror = () => reject(request.error);
                });

                await this.loadTransactions();
                this.showSuccessMessage('Transaction deleted!');
            } catch (error) {
                console.error('Error deleting transaction:', error);
                alert('Failed to delete transaction');
            }
        },

        async deleteTransactionsByPerson(personId) {
            if (!this.db) return;

            return new Promise((resolve, reject) => {
                const tx = this.db.transaction(['transactions'], 'readwrite');
                const store = tx.objectStore('transactions');
                const index = store.index('person_id');
                const request = index.openCursor(IDBKeyRange.only(personId));

                request.onsuccess = (e) => {
                    const cursor = e.target.result;
                    if (cursor) {
                        cursor.delete();
                        cursor.continue();
                    } else {
                        resolve();
                    }
                };

                request.onerror = () => reject(request.error);
            });
        },

        // Form handling
        async savePerson() {
            if (!this.personForm.name.trim()) {
                alert('Please enter a name');
                return;
            }

            try {
                const person = {
                    name: this.personForm.name.trim(),
                    created_at: new Date().toISOString()
                };

                let personId;

                if (this.personForm.id) {
                    await this.updatePerson(this.personForm.id, person);
                    personId = this.personForm.id;
                } else {
                    personId = await this.addPerson(person);
                }

                // Add initial transaction if amount provided
                if (this.personForm.amount && parseFloat(this.personForm.amount) > 0) {
                    await this.addTransaction({
                        person_id: personId,
                        type: this.personForm.type,
                        amount: parseFloat(this.personForm.amount),
                        date: this.personForm.date,
                        created_at: new Date().toISOString()
                    });
                }

                await this.loadData();
                this.showSuccessMessage('Saved successfully!');
                this.closePersonForm();
            } catch (error) {
                console.error('Error saving person:', error);
                alert('Failed to save. Please try again.');
            }
        },

        // Calculations
        getTotalInFlow() {
            return this.transactions
                .filter(t => t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        getTotalOutFlow() {
            return this.transactions
                .filter(t => t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
        },

        getTotalBalance() {
            return this.getTotalInFlow() - this.getTotalOutFlow();
        },

        getPersonBalance(personId) {
            const personTxs = this.transactions.filter(t => t.person_id === personId);
            const received = personTxs
                .filter(t => t.type === 'receive')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
            const given = personTxs
                .filter(t => t.type === 'give')
                .reduce((sum, t) => sum + parseFloat(t.amount || 0), 0);
            return received - given;
        },

        getPersonName(personId) {
            const person = this.people.find(p => p.id === personId);
            return person ? person.name : 'Unknown';
        },

        getPersonTransactionCount(personId) {
            return this.transactions.filter(t => t.person_id === personId).length;
        },

        // UI helpers
        switchView(view) {
            this.currentView = view;
        },

        openPersonForm() {
            this.personForm = {
                id: null,
                name: '',
                type: 'give',
                amount: '',
                date: new Date().toISOString().split('T')[0]
            };
            this.showPersonForm = true;
        },

        closePersonForm() {
            this.showPersonForm = false;
        },

        showSuccessMessage(message) {
            this.successMessage = message;
            this.showSuccess = true;
            setTimeout(() => {
                this.showSuccess = false;
            }, 3000);
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        },

        formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount || 0);
        }
    };
}

// Make available globally for Alpine.js
window.cashApp = cashApp;
