import { router } from '@inertiajs/vue3';
import { useToast } from "@/Components/ui/toast/use-toast";
import { useToastConfig } from "@/Components/ui/toast/toast-config";
import { useThemeStore } from '@/stores/theme/themeStore';

export function useFavorites() {
  const { toast } = useToast();
  const themeStore = useThemeStore();
  const toastConfig = useToastConfig();

  const getToastConfig = (type) => ({
    ...toastConfig,
    class: `${toastConfig.class} ${type === 'success'
        ? themeStore.isDarkMode
          ? `bg-${themeStore.currentTheme.background.dark}/10 border-${themeStore.currentTheme.border}/20`
          : `bg-${themeStore.currentTheme.button}/20`
        : type === 'error'
          ? themeStore.isDarkMode
            ? `bg-destructive/10 border-${themeStore.currentTheme.border}/20`
            : 'bg-destructive/20'
          : themeStore.isDarkMode
            ? `bg-${themeStore.currentTheme.background.dark}/10 border-${themeStore.currentTheme.border}/20`
            : `bg-${themeStore.currentTheme.background.light}/20`
      }`,
  });

  const toggleFavorite = async (modelId, modelType, isFavorited) => {
    try {
      router.post(route('favorites.store'), {
        model_id: modelId,
        model_type: modelType
      }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (page) => {
          if (page.props.flash.success) {
            toast({
              title: isFavorited ? "Removed from favorites" : "Added to favorites",
              description: page.props.flash.success,
              variant: isFavorited ? "default" : "success",
              ...getToastConfig(isFavorited ? 'default' : 'success'),
            });
          }
        },
        onError: (errors) => {
          console.error('Favorite toggle error:', errors);
          toast({
            title: "Error",
            description: "Failed to update favorites",
            variant: "destructive",
            ...getToastConfig('error'),
          });
          return false;
        }
      });

      return true;
    } catch (error) {
      console.error('Favorite toggle error:', error);
      toast({
        title: "Error",
        description: "An error occurred while updating favorites",
        variant: "destructive",
        ...getToastConfig('error'),
      });
      return false;
    }
  };

  return {
    toggleFavorite
  };
} 