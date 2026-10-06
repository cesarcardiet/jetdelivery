import 'package:flutter/rendering.dart';
import 'package:stackfood_multivendor_restaurant/common/widgets/product_shimmer_widget.dart';
import 'package:stackfood_multivendor_restaurant/common/widgets/product_widget.dart';
import 'package:stackfood_multivendor_restaurant/features/restaurant/controllers/restaurant_controller.dart';
import 'package:stackfood_multivendor_restaurant/helper/custom_print_helper.dart';
import 'package:stackfood_multivendor_restaurant/util/dimensions.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

class ProductViewWidget extends StatefulWidget {
  final ScrollController scrollController;
  final String? type;
  final Function(String type)? onVegFilterTap;
  const ProductViewWidget({super.key, required this.scrollController, this.type, this.onVegFilterTap});

  @override
  State<ProductViewWidget> createState() => _ProductViewWidgetState();
}

class _ProductViewWidgetState extends State<ProductViewWidget> {
  @override
  void initState() {
    super.initState();
    Get.find<RestaurantController>().setOffset(1);
    widget.scrollController.addListener(_scrollListener);
  }

  @override
  void dispose() {
    widget.scrollController.removeListener(_scrollListener);
    super.dispose();
  }

  void _scrollListener() {
    if (!widget.scrollController.hasClients) return;
    final RestaurantController rc = Get.find<RestaurantController>();
    if (widget.scrollController.position.pixels == widget.scrollController.position.maxScrollExtent
        && rc.productList != null
        && !rc.isLoading) {
      int pageSize = (rc.pageSize! / 10).ceil();
      if (rc.offset < pageSize) {
        rc.setOffset(rc.offset + 1);
        customPrint('end of the page');
        rc.showBottomLoader();
        rc.getProductList(
          offset: rc.offset.toString(),
          foodType: rc.selectedFoodType,
          stockType: rc.selectedStockType,
          categoryId: rc.categoryId,
        );
      }
    }
    final bool listEmpty = rc.productList != null && rc.productList!.isEmpty;
    if (listEmpty) {
      if (!rc.isFabVisible) {
        rc.showFab();
      }
      return;
    }
    if (widget.scrollController.position.userScrollDirection == ScrollDirection.reverse) {
      if (rc.isFabVisible) {
        rc.hideFab();
      }
    } else if (widget.scrollController.position.userScrollDirection == ScrollDirection.forward) {
      if (!rc.isFabVisible) {
        rc.showFab();
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return GetBuilder<RestaurantController>(builder: (restController) {
      return Column(children: [

        restController.productList != null ? restController.productList!.isNotEmpty ? GridView.builder(
          key: UniqueKey(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisSpacing: Dimensions.paddingSizeDefault,
            mainAxisSpacing:Dimensions.paddingSizeDefault,
            mainAxisExtent: 111,
            crossAxisCount: 1,
          ),
          physics: const NeverScrollableScrollPhysics(),
          shrinkWrap: true,
          itemCount: restController.productList!.length,
          padding: const EdgeInsets.all(Dimensions.paddingSizeDefault),
          itemBuilder: (context, index) {
            return ProductWidget(
              product: restController.productList![index],
              index: index, length: restController.productList!.length, isCampaign: false,
              inRestaurant: true,
            );
          },
        ) : Center(child: Padding(
          padding: const EdgeInsets.only(top: 150),
          child: Text('no_food_available'.tr),
        )) : GridView.builder(
          key: UniqueKey(),
          gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisSpacing: Dimensions.paddingSizeDefault,
            mainAxisSpacing:Dimensions.paddingSizeDefault,
            mainAxisExtent: 112,
            crossAxisCount: 1,
          ),
          physics: const NeverScrollableScrollPhysics(),
          shrinkWrap: true,
          itemCount: 20,
          padding: const EdgeInsets.all(Dimensions.paddingSizeSmall),
          itemBuilder: (context, index) {
            return const ProductShimmerWidget();
          },
        ),

        restController.isLoading ? Center(child: Padding(
          padding: const EdgeInsets.all(Dimensions.paddingSizeSmall),
          child: CircularProgressIndicator(valueColor: AlwaysStoppedAnimation<Color>(Theme.of(context).primaryColor)),
        )) : const SizedBox(),

      ]);
    });
  }
}