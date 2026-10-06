import 'package:stackfood_multivendor_restaurant/api/api_client.dart';
import 'package:stackfood_multivendor_restaurant/features/auth/controllers/auth_controller.dart';
import 'package:stackfood_multivendor_restaurant/helper/route_helper.dart';
import 'package:stackfood_multivendor_restaurant/common/widgets/custom_snackbar_widget.dart';
import 'package:get/get.dart';

class ApiChecker {
  static void checkApi(Response response) {
    if(response.statusCode == 401) {
      Get.find<AuthController>().clearSharedData();
      Get.offAllNamed(RouteHelper.getSignInRoute());
    }else {
      showCustomSnackBar(_localizeApiError(response.statusText));
    }
  }

  /// Convierte mensajes frecuentes del API en inglés a claves ES (app solo español).
  static String? _localizeApiError(String? statusText) {
    if (statusText == null || statusText.isEmpty) return statusText;
    if (statusText == ApiClient.noInternetMessage) {
      return 'api_connection_failed'.tr;
    }
    if (statusText.contains('Please check activation for restaurant app')) {
      return 'api_activation_restaurant_app'.tr;
    }
    if (statusText.contains('Please check activation for deliveryman app')) {
      return 'api_activation_deliveryman_app'.tr;
    }
    if (statusText.startsWith('Please check activation for ')) {
      return statusText.replaceFirst('Please check activation for ', 'Revisa la activación: ');
    }
    if (statusText == 'Service Unavailable') {
      return 'api_service_unavailable'.tr;
    }
    return statusText;
  }
}