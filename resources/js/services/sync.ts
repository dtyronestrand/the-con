import axios from 'axios';

class SyncService {
  private lastSync: string | null = localStorage.getItem('last_sync');
  private apiUrl = import.meta.env.VITE_API_URL || 'http://localhost:8000';
  private syncInterval: number | null = null;

  async pull() {
    const { data } = await axios.get(`${this.apiUrl}/api/sync/pull`, {
      params: { last_sync: this.lastSync }
    });
    
    this.lastSync = data.synced_at;
    localStorage.setItem('last_sync', data.synced_at);
    return data;
  }

  async push(changes: any) {
    await axios.post(`${this.apiUrl}/api/sync/push`, changes);
  }

  startAutoSync(intervalMinutes = 5) {
    this.syncInterval = window.setInterval(() => {
      this.pull().catch(console.error);
    }, intervalMinutes * 60 * 1000);
  }

  stopAutoSync() {
    if (this.syncInterval) clearInterval(this.syncInterval);
  }
}

export default new SyncService();
