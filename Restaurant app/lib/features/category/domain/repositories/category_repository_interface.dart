import 'package:stackfood_multivendor_restaurant/features/restaurant/domain/models/product_model.dart';
import 'package:stackfood_multivendor_restaurant/interface/repository_interface.dart';

abstract class CategoryRepositoryInterface implements RepositoryInterface {
  Future<dynamic> getCategoryList({bool isRestaurantWise = false, String? search});
  Future<dynamic> getSubCategoryList(int? parentID, {bool isRestaurantWise = false});
  Future<ProductModel?> getCategoryItemList({required String offset, required int id, required int isSubCategory});
}