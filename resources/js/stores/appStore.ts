 
import { defineStore } from "pinia";

export const useAppStore = defineStore("app", {
  state: () => ({
    classroomTabs: {} as Record<string, string>,  
  }),

  actions: {
    setActiveTab(classroomId: string | number, tab: string) {
      this.classroomTabs[classroomId] = tab;
    },

    getActiveTab(classroomId: string | number, fallback = "overview") {
      return this.classroomTabs[classroomId] || fallback;
    },
  },

  persist: true,
});
